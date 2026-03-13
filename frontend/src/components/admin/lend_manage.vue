<template>
    <div class="p-5">
        <div class="flex justify-between ">
            <div class="flex gap-5">
        <div class="text-2xl">Lend Management</div>
                <button @click="openModal()" class="rounded-lg bg-cyan-500 p-2 text-white">+ Add Lend</button>
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
        <th>ชื่อผู้ยืม</th>
        <th>ชื่อหนังสือ</th>
        <th>วันที่ยืม</th>
        <th>วันที่ต้องคืน</th>
        <th>สถานะ</th>
        <th>Active</th>
      </tr>
    </thead>
    <tbody>
      <!-- row 1 -->
      <tr v-for="c in filterSearch">
        <th>{{ c.id }}</th>
        <td>{{ getUserName(c.user_id) }}</td>
        <td >{{ getBookName(c.book_id) }}</td>
        <td >{{ formatDate(c.start_date )}}</td>
        <td >{{ formatDate(c.end_time) }}</td>
        <td v-if="c.status === 'borrowing'">
            <button @click="returnBook(c.id)"  class="text-white bg-yellow-500 p-1 px-5 rounded-lg">ยืมอยู่</button>
        </td>
        <td v-else>
            <div  class="text-white bg-cyan-500 p-1 w-14 rounded-lg">คืนแล้ว</div>
        </td>
        <td class="flex gap-3 font-semibold">
            <button v-if="c.status === 'borrowing'" @click="returnBook(c.id)"  class="text-white bg-green-500 p-1 rounded-lg">คืนหนังสือ</button>
            <button @click="deleteLend(c.id)" class="text-white bg-red-500 p-1 rounded-lg">Delete</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
        </div>

        <div v-if="isModal" class="fixed flex justify-center items-center inset-0 bg-black/50">
            <div class="border bg-white rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">--- Add Lend ---</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-1">
                <label for="">User Name</label>
                <select v-model="form.user_id" name="" class="select border p-1 px-3 w-full" id="">
                    <option value="">Select : User</option>
                    <option v-for="u in user" :value="u.id" :key="u.id" >{{ u.name }} </option>
                </select>
                <label for="">Book Name</label>
                <select v-model="form.book_id" name="" class="select border p-1 px-3 w-full" id="">
                    <option value="">Select : Book</option>
                    <option v-for="b in book" :value="b.id" :key="b.id" >{{ b.book_name }} </option>
                </select>
                <label for="">Start Date</label>
                <input v-model="form.start_date" type="datetime-local" class="input border px-3 w-full">
                <label for="">End Date</label>
                <input v-model="form.end_time" type="datetime-local" class="input border px-3 w-full">
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
            lend:[],
            user:[],
            book:[],
            form:{},
            isModal:false,
            filterName:'',
            loading:false,
        }
    },

    mounted(){
        this.loadLend()
        this.loadBook()
        this.loadUser()
    },

    computed:{
        filterSearch(){
            return this.lend.filter(x => {
                 const user = this.user.find(u => u.id === x.user_id)
                 const matchUser =  user ? user.name.toLowerCase().includes(this.filterName.toLowerCase()) : ''

                 const book = this.book.find(b => b.id === x.book_id)
                 const matchBook = book ? book.book_name.toLowerCase().includes(this.filterName.toLowerCase()) : ''

                 return matchUser || matchBook
            })
        }
    },

    

    methods:{
        formatDate(d){
            if(!d) return '-'

            const date = new Date(d)
            
            return date.toLocaleString('th-Th',{
                year:'numeric',
                month:'short',
                day:'2-digit',
                hour:'2-digit',
                minute:'2-digit',
                hour12:false
            })
        },

        getUserName(id){
            const u = this.user.find(x => x.id === id)
            return  u ? u.name : '-'
        },

        getBookName(id){
            const b = this.book.find(x => x.id === id)
            return  b ? b.book_name : '-'
        },

        
        
        openModal(){
            this.isModal = true
            this.form={
                user_id:'',
                book_id:''
            }
        },

        closeModal(){
            this.isModal =false
        },


        async loadLend(){
            this.loading=true
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/lend`,getAut());
            this.lend = await res.json()
            }catch(err){
                return alert('Error:'+err.message)
            }finally{
                this.loading=false
            }
        },

        async loadUser(){
            const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/users`,getAut());
            this.user = await res.json()
        },

        async loadBook(){
            const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/books`,getAut());
            this.book = await res.json()
        },

        async submitForm(){
            if(!this.form.user_id || !this.form.book_id ||  !this.form.end_time){
                    return alert('กรุณากรอกข้อมูลให้ครบ')
                }

                if(this.form.start_date > this.form.end_time){
                    return alert('วันที่เริ่มยืม ไม่สามารถอยู่หลังวันคืนได้')
                }

            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/lend`,{
                    method:'POST',
                    ...getAut(),
                    body:JSON.stringify(this.form)
                })
                const data = await res.json()
                if(!res.ok) throw new Error(data.message)
                alert(data.message);
                this.form={}
                this.loadLend()
                this.closeModal()


            }catch(err){
                return alert('Error: '+err.message)
            }
        },

        async deleteLend(id){
            if(!confirm('คุณแน่ใจใช่ไหมที่จะลบข้อมูลแถวนี้ ?')) return
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/lend/${id}`,{
                    method:'DELETE',
                    ...getAut(),
                })
                const data = await res.json()
                alert(data.message)
                this.loadLend()
                if(!res.ok) throw new Error(data.message)
            }catch(err){
                return alert('Error: '+err.message)
            }
        },

        async returnBook(id){
            if(!confirm('คุณแน่ใจนะที่จะคืนหนังสือ ?')) return
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/lend/soft/${id}`,{
                    method:'DELETE',
                    ...getAut(),
                })
                const data = await res.json()
                alert(data.message)
                this.loadLend()
                if(!res.ok) throw new Error(data.message)
            }catch(err){
                return alert('Error: '+err.message)
            }
        }



    }
}
</script>