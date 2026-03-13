<template>
    <div class="flex justify-center items-center min-h-screen">
        <div class="border rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">Login</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-2">
                <label for="">Email</label>
                <input type="email" v-model="users.email" class="border p-3 rounded-xl" placeholder="Email">
                <label for="">Password</label>
                <input type="password" v-model="users.password" class="border p-3 rounded-xl" placeholder="Password">
                <div class="flex gap-5">
                    <router-link to="/register" class="underline">Register</router-link>
                </div>
                <button type="submit" class="border p-2 rounded-xl bg-cyan-500 text-white">Login</button>
            </form>

        </div>
    </div>
</template>

<script>
import { setUserSession } from '../utilities/api';
export default{
    data(){
        return{
            users:{},
        }
    },


    

    methods:{

        redirectByRole(role){
            // if(role === 'admin') this.$router.push('/dashboard')
            // else if(role === 'user') this.$router.push('/book') 

            if(role === 'admin') window.location.href = '/dashboard'
            else if(role === 'user') window.location.href = '/book'
        },
        
        async submitForm() {
    if (!this.users.email || !this.users.password) {
        return alert('กรุณากรอกข้อมูลให้ครบ!');
    }

    try {
        const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(this.users)
        });

        const data = await res.json();
        
        // 🚩 ดูตรง Console นี้นะครับ ว่ามันพ่นอะไรออกมา
        console.log("Response จาก Laravel:", data); 

        // 🚩 แก้จาก data.err เป็น data.error หรือพ่นข้อความที่ Laravel ส่งมา
        if (!res.ok) {
            throw new Error(data.error || data.message || 'เกิดข้อผิดพลาดจาก Server');
        }

        setUserSession(data.id, data.token, data.role);
        alert('เข้าสู่ระบบสำเร็จ');
        this.redirectByRole(data.role);

    } catch (error) {
        console.error("Catch Error:", error);
        // คราวนี้มันจะ Alert บอกแล้วว่าผิดเพราะอะไร (รหัสผิด หรือ Server พัง)
        return alert('Error: ' + error.message);
    }
}
    }
}
</script>