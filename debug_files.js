const fs = require('fs');

// Read files
const src = fs.readFileSync('index_local_backup.html', 'utf8');
const tgt = fs.readFileSync('index.html', 'utf8');

const srcLines = src.split('\n');
const tgtLines = tgt.split('\n');

console.log('Source lines:', srcLines.length);
console.log('Target lines:', tgtLines.length);

// Find body
for (let i = 0; i < srcLines.length; i++) {
  if (srcLines[i].trim() === '<body>') {
    console.log('Source body at line:', i + 1);
    break;
  }
}

for (let i = 0; i < tgtLines.length; i++) {
  if (tgtLines[i].trim() === '<body>') {
    console.log('Target body at line:', i + 1);
    break;
  }
}

// Show lines 519-525 of source
console.log('\nSource lines 519-525:');
for (let i = 518; i < 525 && i < srcLines.length; i++) {
  console.log(`  ${i+1}: ${srcLines[i].substring(0, 100)}`);
}

console.log('\nTarget lines 519-525:');
for (let i = 518; i < 525 && i < tgtLines.length; i++) {
  console.log(`  ${i+1}: ${tgtLines[i].substring(0, 100)}`);
}
