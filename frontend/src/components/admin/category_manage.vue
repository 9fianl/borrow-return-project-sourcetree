<template>
    <div class="p-5">
        <div class="flex justify-between ">
            <div class="flex gap-5">
        <div class="text-2xl">Category Management</div>
                <button @click="openModal()" class="rounded-lg bg-cyan-500 p-2 text-white">+ Add Category</button>
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
        <th>จำนวนหนังสือแต่ละหมวด</th>
        <th>Active</th>
      </tr>
    </thead>
    <tbody>
      <!-- row 1 -->
      <tr v-for="c in filterCategory">
        <th>{{ c.id }}</th>
        <td>{{ c.category_name }}</td>
        <td >{{ c.books_count ?? 0 }}</td>
        <td class="flex gap-3 font-semibold">
            <button @click="openModal(c)" class="text-white bg-yellow-500 p-1 rounded-lg">Edit</button>
            <button @click="deleteCategory(c.id)" class="text-white bg-red-500 p-1 rounded-lg">Delete</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
        </div>

        <div v-if="isModal" class="fixed flex justify-center items-center inset-0 bg-black/50">
            <div class="border bg-white rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">{{ isEditing ? 'Category Edit' : 'Category Add' }}</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-2">
                <label for="">Category Name</label>
                <input type="text" v-model="form.category_name"  class="border p-3 rounded-xl" placeholder="Category Name">
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
            categories:[],
            form:{},
            isModal:false,
            isEditing:false,
            filterName:'',
            loading:false,
        }
    },

    mounted(){
        this.loadCategory()
    },

    computed:{
        filterCategory(){
            return this.categories.filter(c => {
                 const matchName = c.category_name.toLowerCase().includes(this.filterName.toLowerCase());

                 return matchName
            })
        }
    },

    

    methods:{
        
        openModal(category){
            this.isModal = true
            if(category){
                this.isEditing=true,
                this.form = {...category}
            }else{
                this.isEditing = false,
                this.form = {}
            }
        },

        closeModal(){
            this.isModal =false
        },


        async loadCategory(){
            this.loading = true
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/category`,getAut());
                this.categories = await res.json()
            }catch(err){
                return alert('Error: '+err.message)
            }finally{
            this.loading = false
            }
        },

        async submitForm(){
            if(!this.form.category_name){
                    return alert('กรุณากรอกข้อมูลให้ครบ')
                }

            try{
                const url = this.isEditing 
                            ? `${import.meta.env.VITE_BACKEND}/api/category/${this.form.id}`
                            : `${import.meta.env.VITE_BACKEND}/api/category`
                const method = this.isEditing 
                            ? `PUT`
                            : `POST`
                const res = await fetch(url,{
                    method:method,
                    ...getAut(),
                    body:JSON.stringify(this.form)
                })
                const data = await res.json()
                if(!res.ok) throw new Error(data.message)
                alert(data.message);
                this.form={}
                this.loadCategory()
                this.closeModal()


            }catch(err){
                return alert('Error: '+err.message)
            }
        },

        async deleteCategory(id){
            if(!confirm('คุณแน่ใจใช่ไหมที่จะลบข้อมูลแถวนี้ ?')) return
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/category/${id}`,{
                    method:'DELETE',
                    ...getAut(),
                })
                const data = await res.json()
                alert(data.message)
                this.loadCategory()
                if(!res.ok) throw new Error(data.message)
            }catch(err){
                return alert('Error: '+err.message)
            }
        }



    }
}
</script>