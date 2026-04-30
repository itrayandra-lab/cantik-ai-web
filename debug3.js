const fs = require('fs');

const src = fs.readFileSync('index_local_backup.html', 'utf8');
const tgt = fs.readFileSync('index.html', 'utf8');

console.log('Source length:', src.length, 'bytes');
console.log('Target length:', tgt.length, 'bytes');

// Check first 100 chars of source
console.log('\nFirst 200 chars of source (char codes):');
for (let i = 0; i < 200; i++) {
  const code = src.charCodeAt(i);
  if (code > 31 && code < 127) {
    process.stdout.write(src[i]);
  } else {
    process.stdout.write(`[${code}]`);
  }
}
console.log('\n');

// Check if source has BOM
console.log('Source BOM check:', src.charCodeAt(0), src.charCodeAt(1), src.charCodeAt(2));

// Check around first > in source
const firstGt = src.indexOf('>');
console.log('\nAround first > (index', firstGt, '):');
for (let i = Math.max(0, firstGt - 5); i < Math.min(src.length, firstGt + 10); i++) {
  const code = src.charCodeAt(i);
  console.log(`  [${i}] char: ${code} = '${code > 31 && code < 127 ? src[i] : '?'}'`);
}

// Find first actual text content in source
console.log('\nSearching for first text content in source...');
let found = 0;
for (let i = 0; i < src.length - 1; i++) {
  if (src[i] === '>') {
    let j = i + 1;
    while (j < src.length && src[j] !== '<') j++;
    const text = src.substring(i + 1, j);
    const trimmed = text.trim();
    if (trimmed && trimmed.length > 2) {
      // Check if it has normal chars
      let hasNormal = false;
      for (let k = 0; k < trimmed.length; k++) {
        const code = trimmed.charCodeAt(k);
        if (code > 31 && code < 127) { hasNormal = true; break; }
      }
      if (hasNormal) {
        console.log(`Found at ${i}: "${trimmed.substring(0, 80)}"`);
        found++;
        if (found >= 10) break;
      }
    }
  }
}
