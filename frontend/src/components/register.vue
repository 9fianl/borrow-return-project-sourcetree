<template>
    <div class="flex justify-center items-center min-h-screen">
        <div class="border rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">Register</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-2">
                <label for="">Email</label>
                <input type="email" v-model="users.email" class="border p-3 rounded-xl" placeholder="Email">
                <label for="">Password</label>
                <input type="password" v-model="users.password" class="border p-3 rounded-xl" placeholder="Password">
                <label for="">Fullname</label>
                <input type="text" v-model="users.name" class="border p-3 rounded-xl" placeholder="Name">
                <div class="flex gap-5">
                    <router-link to="/login" class="underline">Login</router-link>
                </div>
                <button type="submit" class="border p-2 rounded-xl bg-cyan-500 text-white">Register</button>
            </form>

        </div>
    </div>
</template>

<script>
export default{
    data(){
        return{
            users:{},
        }
    },


    

    methods:{
        
        async submitForm(){
            if(!this.users.email || !this.users.password || !this.users.name){
                return alert('กรุณากรอกข้อมูลให้ครบ!')
            }

            if(this.users.password.length<6){
                return alert('รหัสผ่านต้องมีอย่างน้อย 6 ตัว') 
            }

            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/register`,{
                    method:'POST',
                    headers:{
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body:JSON.stringify(this.users)
                })
                const data = await res.json()
                let errorMessage = Object.values(data)[0][0];
                if(!res.ok) throw new Error(errorMessage)
                alert(data.message)
             this.users =''


                
            }catch(error){
                return alert('Error: '+error.message)
            }
        }
    }
}
</script>