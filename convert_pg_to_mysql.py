#!/usr/bin/env python3
"""Convert PostgreSQL dump to MySQL format."""

import re
import sys

INPUT_FILE = 'd:/projects/projects/ajab/platform_dev.sql'
OUTPUT_FILE = 'd:/projects/projects/ajab/platform_dev_mysql.sql'

def convert_value(val, is_bool=False):
    """Convert a single COPY value to MySQL INSERT value."""
    if val == '\\N':
        return 'NULL'
    if is_bool:
        if val == 't':
            return '1'
        elif val == 'f':
            return '0'
    # Escape for MySQL string
    escaped = val.replace("\\", "\\\\").replace("'", "\\'")
    return "'" + escaped + "'"

def parse_copy_columns(line):
    """Parse COPY table_name (col1, col2, ...) FROM stdin;"""
    m = re.match(r'COPY\s+(\w+)\s*\(([^)]+)\)\s*FROM\s+stdin\s*;', line)
    if m:
        table_name = m.group(1)
        columns = [c.strip() for c in m.group(2).split(',')]
        return table_name, columns
    return None, None

def convert_create_table(lines, auto_inc_tables):
    """Convert a CREATE TABLE block to MySQL syntax."""
    first_line = lines[0]
    m = re.match(r'CREATE TABLE\s+(\w+)\s*\(', first_line)
    if not m:
        return None, None, set()
    table_name = m.group(1)

    full_text = '\n'.join(lines)
    inner_match = re.search(r'CREATE TABLE\s+\w+\s*\(\s*\n(.*)\n\s*\)\s*;', full_text, re.DOTALL)
    if not inner_match:
        return None, None, set()

    inner_text = inner_match.group(1)

    # Split into column definitions
    col_defs = []
    current = ''
    paren_depth = 0
    for char in inner_text:
        if char == '(':
            paren_depth += 1
            current += char
        elif char == ')':
            paren_depth -= 1
            current += char
        elif char == ',' and paren_depth == 0:
            col_defs.append(current.strip())
            current = ''
        else:
            current += char
    if current.strip():
        col_defs.append(current.strip())

    is_auto_inc = table_name in auto_inc_tables
    bool_col_names = set()
    converted_cols = []

    for col_def in col_defs:
        col_def = col_def.strip()
        if not col_def:
            continue
        if col_def.upper().startswith('CONSTRAINT'):
            continue

        parts = col_def.split()
        if len(parts) < 2:
            continue
        col_name = parts[0]
        new_def = col_def

        # Remove PostgreSQL type casts BEFORE type conversion
        # e.g., 'c_user'::character varying -> 'c_user'
        new_def = re.sub(r"'([^']*)'::character varying", r"'\1'", new_def)
        new_def = re.sub(r"'([^']*)'::text", r"'\1'", new_def)

        # Type conversions
        new_def = re.sub(r'character varying\((\d+)\)', r'VARCHAR(\1)', new_def)
        new_def = re.sub(r'character varying\b(?!\()', 'VARCHAR(255)', new_def)
        new_def = re.sub(r'timestamp without time zone', 'DATETIME', new_def)
        new_def = re.sub(r'timestamp with time zone', 'DATETIME', new_def)

        if 'boolean' in col_def.lower():
            bool_col_names.add(col_name)
        new_def = re.sub(r'\bboolean\b', 'TINYINT(1)', new_def, flags=re.IGNORECASE)
        new_def = re.sub(r'\binteger\b', 'INT', new_def, flags=re.IGNORECASE)
        new_def = re.sub(r'\bbytea\b', 'BLOB', new_def, flags=re.IGNORECASE)
        new_def = re.sub(r'\bdouble precision\b', 'DOUBLE', new_def, flags=re.IGNORECASE)
        new_def = re.sub(r'\bserial\b', 'INT NOT NULL AUTO_INCREMENT', new_def, flags=re.IGNORECASE)

        # Convert boolean defaults
        new_def = re.sub(r'\bDEFAULT\s+false\b', 'DEFAULT 0', new_def, flags=re.IGNORECASE)
        new_def = re.sub(r'\bDEFAULT\s+true\b', 'DEFAULT 1', new_def, flags=re.IGNORECASE)

        # Handle auto_increment for id column
        if col_name == 'id' and is_auto_inc:
            new_def = re.sub(r"\s*DEFAULT\s+nextval\('[^']*'(?:::regclass)?\)", '', new_def)
            if 'NOT NULL' not in new_def.upper():
                new_def = re.sub(r'^(\w+)\s+(INT)', r'\1 \2 NOT NULL', new_def)
            if 'AUTO_INCREMENT' not in new_def:
                new_def = new_def.rstrip() + ' AUTO_INCREMENT'
            new_def = new_def.rstrip() + ' PRIMARY KEY'

        # Backtick column name
        new_def = re.sub(r'^(\w+)', lambda m: '`' + m.group(1) + '`', new_def)
        converted_cols.append('  ' + new_def)

    result = f'DROP TABLE IF EXISTS `{table_name}`;\n'
    result += f'CREATE TABLE `{table_name}` (\n'
    result += ',\n'.join(converted_cols)
    result += '\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n'

    return result, table_name, bool_col_names

def collect_full_statement(lines, start_idx):
    """Collect a multi-line SQL statement starting at start_idx until semicolon."""
    stmt = lines[start_idx].strip()
    idx = start_idx
    while not stmt.rstrip().endswith(';'):
        idx += 1
        if idx >= len(lines):
            break
        stmt += ' ' + lines[idx].strip()
    return stmt, idx + 1

def main():
    with open(INPUT_FILE, 'r', encoding='utf-8', errors='replace') as f:
        lines = f.readlines()
    lines = [line.rstrip('\n').rstrip('\r') for line in lines]

    # First pass: identify auto-increment tables
    auto_inc_tables = set()
    for line in lines:
        m = re.match(r"ALTER TABLE ONLY (\w+) ALTER COLUMN id SET DEFAULT nextval\('", line.strip())
        if m:
            auto_inc_tables.add(m.group(1))

    # Patterns to skip (single-line)
    skip_patterns = [
        r'^SET\s+',
        r'^CREATE EXTENSION',
        r'^COMMENT ON EXTENSION',
        r'^SELECT pg_catalog\.setval',
        r'^REVOKE\s+',
        r'^GRANT\s+',
    ]
    skip_re = [re.compile(p) for p in skip_patterns]

    output_lines = []
    output_lines.append('-- MySQL dump converted from PostgreSQL')
    output_lines.append('-- Generated by pg_to_mysql converter')
    output_lines.append('')
    output_lines.append("SET NAMES utf8mb4;")
    output_lines.append("SET FOREIGN_KEY_CHECKS = 0;")
    output_lines.append("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';")
    output_lines.append('')

    table_bool_cols = {}
    i = 0

    while i < len(lines):
        line = lines[i]
        stripped = line.strip()

        # Skip empty lines
        if stripped == '':
            i += 1
            continue

        # Skip comments
        if stripped.startswith('--'):
            # Keep useful section comments
            if 'Type: TABLE;' in stripped or 'Type: TABLE DATA;' in stripped:
                output_lines.append('')
                output_lines.append(stripped)
            elif 'Type: FK CONSTRAINT' in stripped:
                output_lines.append('')
                output_lines.append(stripped)
            elif 'Type: CONSTRAINT' in stripped:
                output_lines.append('')
                output_lines.append(stripped)
            i += 1
            continue

        # Skip single-line patterns
        skip = False
        for pat in skip_re:
            if pat.match(stripped):
                skip = True
                break
        if skip:
            i += 1
            continue

        # Skip CREATE SEQUENCE (multi-line)
        if stripped.startswith('CREATE SEQUENCE'):
            while i < len(lines) and not lines[i].strip().endswith(';'):
                i += 1
            i += 1
            continue

        # Handle CREATE TABLE
        if stripped.startswith('CREATE TABLE'):
            table_lines = [stripped]
            i += 1
            while i < len(lines):
                table_lines.append(lines[i].rstrip())
                if lines[i].strip() == ');':
                    break
                i += 1
            i += 1
            result, table_name, bool_cols = convert_create_table(table_lines, auto_inc_tables)
            if result:
                output_lines.append('')
                output_lines.append(result)
                table_bool_cols[table_name] = bool_cols
            continue

        # Handle COPY ... FROM stdin -> INSERT INTO
        if stripped.startswith('COPY '):
            table_name, columns = parse_copy_columns(stripped)
            if table_name and columns:
                bool_cols = table_bool_cols.get(table_name, set())
                col_is_bool = [col in bool_cols for col in columns]
                i += 1
                insert_count = 0
                while i < len(lines):
                    data_line = lines[i]
                    if data_line.strip() == '\\.':
                        i += 1
                        break
                    values = data_line.split('\t')
                    mysql_values = []
                    for j, val in enumerate(values):
                        is_bool = col_is_bool[j] if j < len(col_is_bool) else False
                        mysql_values.append(convert_value(val, is_bool))
                    backtick_cols = ', '.join('`' + c + '`' for c in columns)
                    vals_str = ', '.join(mysql_values)
                    output_lines.append(f"INSERT INTO `{table_name}` ({backtick_cols}) VALUES ({vals_str});")
                    insert_count += 1
                    i += 1
                if insert_count > 0:
                    output_lines.append('')
                continue

        # Handle ALTER TABLE statements
        if stripped.startswith('ALTER TABLE'):
            stmt, next_i = collect_full_statement(lines, i)
            i = next_i

            # Skip OWNER TO
            if 'OWNER TO' in stmt:
                continue

            # Skip ALTER SEQUENCE
            if 'ALTER SEQUENCE' in stmt:
                continue

            # Skip SET DEFAULT nextval
            if 'SET DEFAULT nextval' in stmt:
                continue

            # Handle ADD CONSTRAINT ... FOREIGN KEY
            fk_match = re.match(
                r'ALTER TABLE ONLY (\w+)\s+ADD CONSTRAINT (\w+)\s+FOREIGN KEY\s*\((\w+)\)\s*REFERENCES\s+(\w+)\s*\((\w+)\)(.*);',
                stmt
            )
            if fk_match:
                tbl = fk_match.group(1)
                constraint = fk_match.group(2)
                fk_col = fk_match.group(3)
                ref_tbl = fk_match.group(4)
                ref_col = fk_match.group(5)
                extra = fk_match.group(6).strip()
                extra_clause = (' ' + extra) if extra else ''
                output_lines.append(
                    f"ALTER TABLE `{tbl}` ADD CONSTRAINT `{constraint}` "
                    f"FOREIGN KEY (`{fk_col}`) REFERENCES `{ref_tbl}` (`{ref_col}`){extra_clause};"
                )
                continue

            # Handle ADD CONSTRAINT ... PRIMARY KEY
            pk_match = re.match(
                r'ALTER TABLE ONLY (\w+)\s+ADD CONSTRAINT \w+\s+PRIMARY KEY\s*\((\w+)\)\s*;',
                stmt
            )
            if pk_match:
                tname = pk_match.group(1)
                pk_col = pk_match.group(2)
                # Skip if auto_inc table (already has inline PRIMARY KEY)
                if tname not in auto_inc_tables:
                    output_lines.append(f"ALTER TABLE `{tname}` ADD PRIMARY KEY (`{pk_col}`);")
                continue

            # Skip anything else (remaining ALTER TABLE statements)
            continue

        # Skip anything else not handled
        i += 1

    # Footer
    output_lines.append('')
    output_lines.append('SET FOREIGN_KEY_CHECKS = 1;')
    output_lines.append('')

    with open(OUTPUT_FILE, 'w', encoding='utf-8') as f:
        f.write('\n'.join(output_lines))

    print(f"Conversion complete. Output written to {OUTPUT_FILE}")
    print(f"Total output lines: {len(output_lines)}")

if __name__ == '__main__':
    main()
