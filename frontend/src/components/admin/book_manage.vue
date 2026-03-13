<template>
    <div class="p-5">
        <div class="flex justify-between ">
            <div class="flex gap-5">
        <div class="text-2xl">Book Management</div>
                <button @click="openModal()" class="rounded-lg bg-cyan-500 p-2 text-white">+ Add Book</button>
            </div>
                <div class="flex gap-5">
                    <input v-model="filterName" type="text" class="border p-2 rounded-lg" placeholder="Search">
                <select v-model="filterCategory" name="" class="select border p-1 px-3 w-full" id="">
                    <option value="">Select : Category</option>
                    <option v-for="c in category" :value="c.id" :key="c.id" >{{ c.category_name }} </option>
                </select>
                </div>
        </div>
        <hr class="my-4">
        <div class="overflow-x-auto">
  <table class="table border">
    <!-- head -->
    <thead class="bg-gray-100">
      <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Active</th>
      </tr>
    </thead>
    <tbody>
      <!-- row 1 -->
      <tr v-for="b in fillterBooks" class="border-b">
        <td>
          <div class="flex items-center gap-3">
            <div class="avatar">
              <div class="mask mask-squircle h-12 w-12">
                <img
                  :src="getImage(b.image_path)"
                  alt="Avatar Tailwind CSS Component" class="w-full h-full object-cover" />
              </div>
            </div>
            <div>
              <div class="font-bold">{{ b.book_name }}</div>
              <div class="text-sm opacity-50">ปีที่พิมพ์ {{ b.year }}</div>
            </div>
          </div>
        </td>
        <td>
          {{ getCategoryName(b.category_id) }}
          <br />
          <span v-if="b.lends_exists" class="badge badge-ghost badge-sm bg-orange-300">
            สถานะ: ถูกยืม
          </span>
          <span v-else class="badge badge-ghost badge-sm bg-green-300">
            สถานะ: ว่าง
          </span>
          
        </td>
        <th class="flex gap-2 text-white">
          <button @click="openModalDetail(b)" class="btn border bg-cyan-500 rounded-lg p-1 ">Details</button>
          <button @click="openModal(b)"  class="btn border bg-yellow-500 rounded-lg p-1 px-3">Edit</button>
          <button @click="deleteBook(b.id)" class="btn border bg-red-500 rounded-lg p-1 ">Delete</button>
        </th>
      </tr>
      
    </tbody>
    
  </table>
</div>
    </div>

    <div v-if="isModal"  class="fixed flex justify-center items-center inset-0 bg-black/50">
            <div class="border bg-white rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">{{ isEditing ? 'Books Edit' : 'Books Add' }}</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-1">
                <img  :src=" imagePreview || getImage(form.image_path) " class="h-64 w-full object-cover">
                <input @change="onFileChane" type="file" class="file-input border w-full ">
                <label for="">Books Name</label>
                <input type="text" v-model="form.book_name"  class="border p-3 rounded-xl" placeholder="Books Name">
                <label for="">ปีที่พิมพ์</label>
                <input type="input" @input="validateYear" maxlength="4" v-model="form.year"  class="border p-3 rounded-xl" placeholder="ปีที่พิมพ์">
                <label for="">Category Name</label>
                <select v-model="form.category_id" name="" class="select border p-1 px-3 w-full" id="">
                    <option value="">Select : Category</option>
                    <option v-for="c in category" :value="c.id" :key="c.id" >{{ c.category_name }} </option>
                </select>
                <button type="button" @click="closeModal()" class="border p-2 rounded-xl bg-gray-500 text-white">Back</button>
                <button type="submit" class="border p-2 rounded-xl bg-cyan-500 text-white">Save</button>
            </form>

        </div>
        </div>


        <div v-if="isModalDetail"  class="fixed flex justify-center items-center inset-0 bg-black/50">
            <div class="border bg-white rounded-lg p-5 w-full max-w-lg">
            <div class="text-2xl text-center font-semibold">Detail Book</div>
            <hr class="my-2">
            <form @submit.prevent="submitForm" class="flex flex-col gap-1">
                <img  :src="getImage(detailBook.image_path)" class="h-64 object-cover w-full">
                <hr class="my-2">
                <div class="text-lg">รายละเอียดข้อมูลหนังสือ</div>
                <div>ชื่อหนังสือ: {{ detailBook.book_name }}</div>
                <div>ปีที่พิมพ์: ปี {{ detailBook.year }}</div>
                <div>หมวดหมู่: {{ getCategoryName(detailBook.category_id) }}</div>
                <div><span v-if="detailBook.lends_exists" class="badge badge-ghost badge-sm bg-orange-300">
            สถานะ: ถูกยืม
          </span>
          <span v-else class="badge badge-ghost badge-sm bg-green-300">
            สถานะ: ว่าง
          </span></div>
                <hr class="my-4">
                <button type="button" @click="closeModalDetail()" class="border p-2 rounded-xl bg-gray-500 text-white">Back</button>
            </form>

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
            books:[],
            category:[],
            filterName:'',
            filterCategory:'',
            isModal:false,
            isEditing:false,
            form:'',
            selectImage:null,
            imagePreview:null,

            isModalDetail:false,
            detailBook:'',

            loading:false,

        }
    },

    mounted(){
        this.loadBooks()
        this.loadCategory()
    },

    computed:{
        fillterBooks(){
            return this.books.filter(x => {
                const matchName = x.book_name.toLowerCase().includes(this.filterName.toLowerCase())
                const matchCategory = this.filterCategory === '' || x.category_id === this.filterCategory
                return matchName && matchCategory
            })
        }
    },

    methods:{
        getImage(img){
            if(!img) return 'https://images.wsj.net/im-924541/?width=1280&size=1.33333333" alt="" class="max-h-60 w-full object-cover'
            return `${import.meta.env.VITE_BACKEND}/storage/${img}`
        },



        
        getCategoryName(id){
            const cat = this.category.find(c=> c.id === id)
            return cat ? cat.category_name : 'ไม่ระบุหมวดหมู่'
        },

        validateYear(){
            this.form.year =  this.form.year.replace(/[^0-9]/g, '')
        },
        async loadBooks(){
            this.loading = true
            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/books`,getAut())
                this.books = await res.json()
            }catch(err){
                return alert('Error:'+err.message)
            }finally{
                this.loading = false
            }
        },

        async loadCategory(){
            const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/category`,getAut())
            this.category = await res.json()
        },

        openModal(books){
            this.isModal=true
            if(books){
                this.isEditing=true,
                this.form = {...books}
            }else{
                this.isEditing=false,
                this.form ={
                    category_id:'',
                }
            }
        },

        closeModal(){
            this.isModal=false
            this.imagePreview = null; 
            this.selectImage = null;
        },

        openModalDetail(books){
            this.detailBook = books
            this.isModalDetail =true
            
        },

        closeModalDetail(){
            this.isModalDetail=false
            this.form ={}
            this.imagePreview = null; 
            this.selectImage = null;
        },

        onFileChane(e){
            const file = e.target.files[0]
            if(file){
                this.selectImage = file;
                this.imagePreview = URL.createObjectURL(file)
            }
        },

        async submitForm(){
            if(!this.form.book_name || !this.form.year || !this.form.category_id){
                return alert('กรุณากรอกข้อมูลให้ครบ')
            }

            try{
                const form = new FormData();
                form.append('book_name', this.form.book_name)
                form.append('year', this.form.year)
                form.append('category_id', this.form.category_id)

                if(this.selectImage){
                    form.append('image_path', this.selectImage)
                }

                if(this.isEditing){
                    form.append('_method','PUT')
                }



                const url = this.isEditing 
                ? `${import.meta.env.VITE_BACKEND}/api/books/${this.form.id}`
                : `${import.meta.env.VITE_BACKEND}/api/books` 
                
                const res =await fetch(url,{
                    method:'POST',
                    headers: {
                // ดึงเฉพาะ Authorization มา ห้ามเอา Content-Type มาครับ
                'Authorization': getAut().headers.Authorization 
            },
                    body:form
                })
                const data = await res.json()
                alert(data.message)
                this.loadBooks()
                this.closeModal()
                if(!res.ok) throw new Error(data.message)
            }catch(err){
                return alert('Error: '+err.message)
            }
        },

        async deleteBook(id){
            if(!confirm('คุณแน่ใจใช่ไหมที่จะลบข้อมูลแถวนี้')) return 

            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/books/${id}`,{
                    method:'DELETE',
                    ...getAut()
                })
                const data = await res.json()
                alert(data.message)
                this.loadBooks()
                if(!res.ok) throw new Error(data.message)
            }catch(err){
                return alert('Error: '+err.message)
            }
        }

        
    }
}
</script>