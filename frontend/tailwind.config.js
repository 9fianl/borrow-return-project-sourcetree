/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,tsx,jsx}'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui')
  ],
  daisyui: {
    // 🌟 ใส่รายชื่อธีมที่อยากใช้ตรงนี้
    themes: ["light", "dark", "cupcake", "retro"] 
  }
}

