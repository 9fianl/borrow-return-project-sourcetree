<template>
    <div class="p-5">
        <div class="flex justify-between ">
            <div class="flex gap-5">
        <div class="text-2xl">User Management</div>
            </div>
                <input v-model="filterName" type="text" class="border p-2 rounded-lg" placeholder="Search">
        </div>
        <hr class="my-4">
        <div class="w-full">
            <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
  <table class="table">
    <!-- head -->
    <thead class="bg-gray-100">
      <tr>
        <th></th>
        <th>Name</th>
        <th>Role</th>
        <th>Email</th>
        <th>วันที่สมัคร</th>
        <th>Active</th>
      </tr>
    </thead>
    <tbody>
      <!-- row 1 -->
      <tr v-for="c in filterCategory">
        <th>{{ c.id }}</th>
        <td>{{ c.name }}</td>
        <td >{{ c.role  }}</td>
        <td >{{ c.email  }}</td>
        <td >{{ formatDate(c.created_at ) }}</td>
        <td class="flex gap-3 font-semibold">
            <button @click="openModal(c)" class="text-white bg-yellow-500 p-1 rounded-lg">Edit</button>
            <button v-if="c.id.toString() !== myId" @click="deleteCategory(c.id)" class="text-white bg-red-500 p-1 rounded-lg">Delete</button>
            <p v-else  class="text-gray-500 p-1 rounded-lg">ตัวเอง</p>
        </td>
      </tr>
    </tbody>
  </table>
</div>
        </div>

        <div v-if="isModal" class="fixed flex justify-center items-center inset-0 bg-black/50">
            <div class="border bg-white rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">User Edit</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-1">
                <label for="">User Name</label>
                <input type="text" v-model="form.name"  class="border p-3 rounded-xl" placeholder="Category Name">
                <div class="bg-gray-100 p-5 flex flex-col  gap-2 rounded-lg border my-2">
                    <label for="">Email && Password (กรอกถ้าคุณต้องการเปลี่ยนรหัสผ่าน)</label>
                <input type="text" v-model="form.email"  class="border p-3 rounded-xl" placeholder="Email">
                <input type="password" v-model="form.password"  class="border p-3 rounded-xl" placeholder="Password">
                </div>
                <button type="button" @click="closeModal()" class="border p-2 rounded-xl bg-gray-500 text-white">Back</button>
                <button type="submit" class="border p-2 rounded-xl bg-cyan-500 text-white">Save</button>
            </form>

        </div>
        </div>


        
    </div>

    <div v-if="loading">
            <div class="flex justify-center items-center min-h-96 text-4xl">กำลังโหลด.....</div>
        </div>
</template>

<script>
import { getAut } from '../../utilities/api';

export default{
    data(){
        return{
            user:[],
            form:{},
            isModal:false,
            isEditing:false,
            filterName:'',
            myId:localStorage.getItem('id'),
            loading:false,
        }
    },

    mounted(){
        this.loadUser()
    },

    computed:{
        filterCategory(){
            return this.user.filter(c => {
                 const matchName = c.name.toLowerCase().includes(this.filterName.toLowerCase());

                 return matchName
            })
        }
    },

    

    methods:{
        
        openModal(user){
            this.isModal = true
            this.isEditing=true,
            this.form = {...user}
        },

        closeModal(){
            this.isModal =false
        },


        async loadUser(){
            this.loading=true
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/users`,getAut());
            this.user = await res.json()
            }catch(err){
                return alert('Error: '+err.message)
            }finally{
                this.loading = false
            }
        },

        async submitForm(){
            if(!this.form.name || !this.form.email ){
                    return alert('กรุณากรอกข้อมูลให้ครบ')
                }

            try{
                
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/users/${this.form.id}`,{
                    method:'PUT',
                    ...getAut(),
                    body:JSON.stringify(this.form)
                })
                const data = await res.json()
                if(!res.ok) throw new Error(data.message)
                alert(data.message);
                this.form={}
                this.loadUser()
                this.closeModal()


            }catch(err){
                return alert('Error: '+err.message)
            }
        },

        async deleteCategory(id){
            if(!confirm('คุณแน่ใจใช่ไหมที่จะลบข้อมูลแถวนี้ ?')) return
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/users/${id}`,{
                    method:'DELETE',
                    ...getAut(),
                })
                const data = await res.json()
                alert(data.message)
                this.loadUser()
                if(!res.ok) throw new Error(data.message)
            }catch(err){
                return alert('Error: '+err.message)
            }
        },

        formatDate(d){
            if(!d) return '-'

            const data = new Date(d)

            return data.toLocaleDateString('th-th',{
                year:'numeric',
                month:'short',
                day:'2-digit',
                hour:'2-digit',
                minute:'2-digit',
                hour12:false
            })
        },



    }
}
</script>