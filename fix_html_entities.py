import html

# Read the file
with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Decode HTML entities
fixed_content = html.unescape(content)

# Write back
with open('index.html', 'w', encoding='utf-8') as f:
    f.write(fixed_content)

print("✅ HTML entities decoded successfully!")
print(f"File size: {len(fixed_content)} characters")
