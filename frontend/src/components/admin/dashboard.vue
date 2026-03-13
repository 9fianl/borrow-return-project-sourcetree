<template>
    <div class="p-5">
      <div class="flex justify-between ">
        <div text-2xl>
        DashBoard Detail
        </div>
    <router-link to="/category_manage" class="w-40 rounded-lg text-white bg-cyan-500 p-2">จัดการหมวดหมู่</router-link>
      </div>
      <hr class="my-4">
        <div class="stats shadow w-full p-5 gap-5">
  <div class="stat border rounded-lg ">
    <div class="stat-figure text-secondary">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        class="inline-block h-8 w-8 stroke-current"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        ></path>
      </svg>
    </div>
    <div class="stat-title">จำนวนหนังสือทั้งหมด</div>
    <div class="stat-value">{{ books }}</div>
    <router-link to="/book_manage" class="w-40 rounded-lg text-white bg-cyan-500 p-2">จัดการหนังสือ</router-link>
  </div>

  <div class="stat border rounded-lg">
    <div class="stat-figure text-secondary">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        class="inline-block h-8 w-8 stroke-current"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
        ></path>
      </svg>
    </div>
    <div class="stat-title">จำนวนหนังที่ถูกยืมอยู่ตอนนี้</div>
    <div class="stat-value">{{ lends }}</div>
    <router-link to="/lend_manage" class="w-40 rounded-lg text-white bg-cyan-500 p-2">รายการยืม</router-link>
  </div>

  <div class="stat border rounded-lg">
    <div class="stat-figure text-secondary">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        class="inline-block h-8 w-8 stroke-current"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
        ></path>
      </svg>
    </div>
    <div class="stat-title">จำนวนผู้ใช้ทั้งหมด</div>
    <div class="stat-value">{{ users }}</div>
  </div>
</div>
    </div>
    
</template>

<script>
import { getAut } from '../../utilities/api';
export default{
    data() {
        return {
            books: 0,
            users: 0,
            lends: 0
        }
    },

    mounted() {
        this.loadDashboardData();
    },

   

  methods: {
    async loadDashboardData() {
        try {
            const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/dashboard/stats`, getAut());
            const data = await res.json();
            
            this.books = data.books;
            this.users = data.users;
            this.lends = data.lends;
        } catch (error) {
            console.error("โหลดข้อมูลแดชบอร์ดไม่สำเร็จ:", error);
        }
    }
}
  
}
</script>