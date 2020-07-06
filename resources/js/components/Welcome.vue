<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-5" align="center">
                <h1>My Blogs</h1>
            </div>
            <div class="col-md-12 mt-5">
                <form class="" @submit.prevent="search_it">
                    <div class="input-group input-group-sm">
                        <input type="text" v-model="search" @keyup="search_it" class="form-control form-control-navbar" 
                        placeholder="Search"
                        aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-success" @click="search_it">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-3 mr-5" v-for="blog in blogs" :key="blog.blog_id">
                <div class="card" style="width: 18rem;">
                    <img :src="'/images/blog_photo/' + blog.blog_image" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-text">{{ blog.blog_title }}</h5>
                        <router-link :to="{ path: '/read_more', query: { 
                            blog_id: blog.blog_id, 
                        }}">
                            Read More >>
                        </router-link>
                        <!-- <router-link to="/read_more">Read more >></router-link> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                search: '',
                blogs: {},
            }
        },
        methods: {
            loadBlogs(){
                axios.get('api/get_all_blogs').then(({ data }) => (this.blogs = data));
            },
            search_it(){
                if(this.search == ''){
                    this.loadBlogs();
                }
                else{
                    axios.get('api/welcome_search_blog/' + this.search)
                    .then((data) => {
                        this.blogs = data.data
                    })
                    .catch(() => {
                        console.log('error');
                    })
                }
            }
        },
        mounted() {
            this.loadBlogs();
        }
    }
</script>
