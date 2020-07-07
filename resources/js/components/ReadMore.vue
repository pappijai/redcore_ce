<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12" align="center" v-for="blog in blogs" :key="blog.blog_id">
                <img :src="'/images/blog_photo/' + blog.blog_image" class="img-fluid" alt="Responsive image">
                <h1 class="mt-2">{{ blog.blog_title }}</h1>
                <p class="mt-2">{{ blog.blog_description }}</p>
                <small>Date created: {{ blog.created_at | myDate}}</small>
                <br/>
                <router-link to="/" class="mb-2 btn btn-success">Back</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                id: '',
                blogs: {},
            }
        },
        methods: {
            getBlog(blog_id){
                axios.get('api/get_blogs/'+blog_id)
                .then(({ data }) => {
                    this.blogs = data;
                })
                .catch(({ data }) => {
                    console.log(data);
                })
            }
        },
        mounted() {
            this.id = this.$route.query.blog_id;
            this.getBlog(this.id)
        }
    }
</script>
