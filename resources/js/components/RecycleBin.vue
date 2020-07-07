<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header bgc-orange">
                        <h3 class="card-title text-orange"><i class="fas fa-trash"></i> Recycle Bin Table</h3>
                        <div class="card-tools">
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="blog_table" class="display table table-stripped table-hover">
                            <thead>
                                <tr>
                                    <th>Date Deleted</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date Created</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="db_blog in db_blogs" :key="db_blog.db_id">
                                    <td>{{db_blog.created_at | myDate}}</td>
                                    <td>{{db_blog.db_title}}</td>
                                    <td>{{db_blog.db_description}}</td>
                                    <td>{{db_blog.db_created | myDate}}</td>
                                    <td>
                                        <button class="btn btn-success" @click="recycleBlog(db_blog.db_id)">
                                            <i class="fas fa-recycle text-white"></i>    
                                        </button>
                                        <button class="btn btn-danger" @click="deleteBlog(db_blog.db_id)">
                                            <i class="fas fa-trash text-white"></i>    
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewBlogsModal" tabindex="-1" role="dialog" aria-labelledby="viewBlogsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bgc-teal">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        
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
                db_blogs: {},
                form: new Form({
                    db_id: '',
                    db_title : '',
                    db_description : '',
                    db_image : '',
                    db_created : '',
                })
            }
        },
        methods:{
            loadDeletedBlog(){
                axios.get('api/get_deleted_blogs')
                .then(({ data }) => {
                    this.db_blogs = data;
                })
                .catch(({ data }) => {
                    console.log(data);
                })
            },
            recycleBlog(id){
                swal.fire({
                    title: 'Are you sure you want to restore?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    // Send ajax request to server
                    if(result.value){
                        this.form.delete('api/restore_blogs/'+id)
                        .then(({ data }) => {
                            this.loadDeletedBlog();
                            swal.fire(
                                data.title,
                                data.message,
                                data.type
                            )
                            
                        })
                        .catch(({ data }) =>{
                            console.log(data);
                        })
                    }
                })  
            },
            deleteBlog(id){
                swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // Send ajax request to server
                    if(result.value){
                        this.form.delete('api/delete_blogs_permanent/'+id)
                        .then(({ data }) => {
                            this.loadDeletedBlog();
                            swal.fire(
                                data.title,
                                data.message,
                                data.type
                            )
                            
                        })
                        .catch(({ data }) =>{
                            console.log(data);
                        })
                    }
                })  
            },
            getBlogPhoto(){
                let photo = (this.form.blog_image.length > 200) ? this.form.blog_image : "./images/blog_photo/"+this.form.blog_image;

                return photo; 
            },
        },
        created(){
            Fire.$on('searching', () => {
                let query = this.$parent.search;
                if(query == ''){
                    this.loadDeletedBlog();
                }
                else{
                    axios.get('api/search_deleted_blogs/' + query)
                    .then(({ data }) => {
                        this.db_blogs = data
                    })
                    .catch(({ data }) => {
                        console.log(data);
                    })
                }
            })
        },
        mounted() {
            this.loadDeletedBlog();
        }
    }
</script>
