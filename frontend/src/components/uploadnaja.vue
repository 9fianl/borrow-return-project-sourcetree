<template>
    <div class="flex justify-center items-center min-h-screen">
        <form @submit.prevent="submitForm" class="flex flex-col p-5 border rounded-md gap-2">
            <div class="text-xl">Upload Image</div>
            <hr class="my-2">
            <div v-if="status === 'success'" class="bg-green-100 text-center border text-green-500 p-1 rounded-md">Image Uploaded</div>
            <div v-else-if="status === 'error'" class="bg-red-100 text-center border text-red-500 p-1 rounded-md">Fail Image Upload</div>
            <img :src="preview || defaultImage" class="w-full h-36 border rounded-lg object-cover" alt="">
        <input type="file" @change="handleFile" class="border">
        <button type="submit" class="text-white bg-green-500 p-1 rounded-md w-20">Submit</button>
        </form>
    </div>
</template>

<script>

export default{
    data(){
        return{
            selectedFile:null,
            preview:null,
            status:null,
            defaultImage:'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmbhndHNlaNoVJW-Wpbq0ZDR7eAjmrQZryXg&s',
        }
    },

    methods:{
        
        handleFile(e){
            const file = e.target.files[0];
            if(file){
                this.selectedFile = file;
                this.preview = URL.createObjectURL(file);
                this.status = null;
            }
        },

        

        async submitForm(){
            
            if(!this.selectedFile){
                return alert('กรุณาเลือกรูปก่อนคับ');
            }

            const formData = new FormData();
            formData.append('image_path', this.selectedFile);

            try{
                const res = await fetch(`${import.meta.env.VITE_BACKEND}/api/upload`,{
                    method:'POST',
                    body:formData,
                    headers:{
                        'Accept':'application/json'
                    }
                })
                if(!res.ok) throw new Error('อัพโหลดล้มเหลว');
                const data = await res.json();
                this.status = 'success'

            }catch(error){
                this.status = 'error'
                return alert('Error: '+error)
            }
        },
    }
}
</script>