const sharp = require('sharp')
const sizes = [72,96,128,144,152,192,384,512]
Promise.all(sizes.map(s =>
  sharp('public/icons/icon.svg').resize(s,s).png().toFile(`public/icons/icon-${s}.png`)
)).then(() => console.log('Icons generated!'))
