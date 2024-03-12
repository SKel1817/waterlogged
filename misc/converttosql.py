def text_to_sql(file_path, table_name):
    # Open the file for reading
    with open(file_path, 'r', encoding='utf-8') as file:
        lines = file.readlines()
    
    # Define the SQL INSERT prefix
    sql_prefix = f"INSERT INTO {table_name} (id, name, traits, sun, water_freq_weekly) VALUES\n"
    
    # Process each line and format it into an SQL value list
    sql_values = []
    for line in lines:
        # Split the line into fields assuming tab-separated values
        fields = line.strip().split('\t')
        # Format the fields into a SQL value string, ensure to escape single quotes in strings
        formatted_fields = ', '.join(f"'{field.replace("'", "''")}'" if not field.isdigit() else field for field in fields)
        sql_values.append(f"({formatted_fields})")
    
    # Join all SQL values into a single string separated by commas and new lines
    sql_query = sql_prefix + ",\n".join(sql_values) + ";"
    
    # Return the complete SQL INSERT statement
    return sql_query

# Example usage
table_name = 'plants'
file_path = 'misc\source.txt' # Update this to the path of your file

# Generate the SQL insert statement
sql_insert_statement = text_to_sql(file_path, table_name)

# Store the SQL statement in a text file called "result"
with open('result.txt', 'w') as file:
    file.write(sql_insert_statement)

# For demonstration, print the SQL statement
print(sql_insert_statement)
