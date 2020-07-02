<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header bgc-orange">
                        <h3 class="card-title text-orange"><i class="fas fa-newspaper"></i> Blogs Table</h3>
                        <div class="card-tools">
                            <!-- call a function on click to the button -->
                            <button class="btn btn-success text-white" @click="newBlog">Add New <span class="fas fa-newspaper fa-fw"></span></button>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="blog_table" class="display table table-stripped table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date Created</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="blog in blogs" :key="blog.blog_id">
                                    <td>{{blog.blog_title}}</td>
                                    <td>{{blog.blog_description}}</td>
                                    <td>{{blog.created_at | myDate}}</td>
                                    <td>
                                        <button class="btn btn-primary" @click="editBlog(blog)">
                                            <i class="fas fa-edit text-white"></i>    
                                        </button>
                                        <button class="btn btn-danger" @click="deleteBlog(blog.blog_id)">
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

        <div class="modal fade" id="addBlogsModal" tabindex="-1" role="dialog" aria-labelledby="addBlogsModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bgc-teal">
                        <h5 class="modal-title text-orange" id="addBlogsModalLabel">{{editmode ? 'Update Blog':'Add New Blog'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Identify if update or create function -->
                    <form @submit.prevent="editmode ? updateBlog() : createBlog()">
                        <div class="modal-body">
                            
                            <!-- blog title -->
                            <div class="form-group">
                                <label for="blog_title">Blog Title</label>
                                <input v-model="form.blog_title" type="text" name="blog_title" placeholder="Blog Title"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('blog_title') }">
                                <has-error :form="form" field="blog_title"></has-error>
                            </div>
                            
                            <!-- blog description -->
                            <div class="form-group">
                                <label for="blog_description">Blog Description</label>
                                <textarea v-model="form.blog_description" name="blog_description" cols="5" rows="2" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('blog_description') }"></textarea>
                                <has-error :form="form" field="blog_description"></has-error>
                            </div>

                            <!-- blog image  -->
                            <div class="form-group">
                                <label v-if="editmode" for="blog_image">Blog Photo (leave empty if no changes)</label>
                                <label v-else for="blog_image">Blog Photo</label>
                                <input id="blog_image" type="file" class="form-control" @change="UpdateBlogImage" name="blog_image"
                                    :class="{ 'is-invalid': form.errors.has('blog_image') }">
                                <has-error :form="form" field="blog_image"></has-error>
                            </div>

                            <div>
                                <img style="width: 100%;" id="blog_image_container" :src="getBlogPhoto()" class="img-fluid" alt="Responsive image">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                                <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div> 
                    </form>               
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                blogs: {},
                editmode: false,
                form: new Form({
                    blog_id: '',
                    blog_title : '',
                    blog_description : '',
                    blog_image : '',
                })
            }
        },
        methods:{
            loadBlog(){
                axios.get('api/blogs').then(({ data }) => (this.blogs = data));
            },
            newBlog(){
                this.editmode = false
                this.form.reset()
                $('#addBlogsModal').modal('show');   
            },
            createBlog(){
                this.form.post('api/blogs')
                .then(() => {
                    Fire.$emit('AfterCreate');
                    $('#addBlogsModal').modal('hide'); 
                    swal.fire(
                        'Good job!',
                        'Blog created successfully',
                        'success'
                    )
                })
                .catch(() => {
                    swal.fire(
                        'Error',
                        'Error occured.',
                        'error'
                    )
                })
            },
            editBlog(blog){
                this.editmode = true;
                this.form.reset();
                this.form.fill(blog);
                $("#blog_image").val('');
                $('#addBlogsModal').modal('show');      
            },
            updateBlog(){
                this.form.put('api/blogs/'+this.form.blog_id)
                .then(() => {
                    Fire.$emit('AfterUpdate');
                    $('#addBlogsModal').modal('hide');                    
                    swal.fire(
                        'Good job!',
                        'Blog updated successfully',
                        'success'
                    )                      
                })
                .catch(() => {
                    swal.fire(
                        'Error',
                        'Error occured.',
                        'error'
                    )
                }) 
            },
            UpdateBlogImage(e){
                let file = e.target.files[0];
                console.log(file);
                let reader = new FileReader();

                if(file['size'] < 5000000){
                    reader.onloadend = (file) => {
                        //console.log('RESULT', reader.result);
    
                        this.form.blog_image = reader.result;
                    }
                    reader.readAsDataURL(file);
                }
                else{
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file.',

                    })
                }
            },
            deleteBlog(id){
                swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: "You can restore this from the recycle bin.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // Send ajax request to server
                    if(result.value){
                        this.form.delete('api/blogs/'+id)
                        .then(() => {
                            Fire.$emit('AfterDelete');
                            swal.fire(
                                'Success',
                                'Blog deleted successfully',
                                'success'
                            )
                            
                        })
                        .catch(() =>{
                            swal.fire(
                                'Error',
                                'There was something wrong.',
                                'error'
                            )
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
            this.loadBlog();

            Fire.$on('searching', () => {
                let query = this.$parent.search;
                if(query == ''){
                    this.loadBlog();
                }
                else{
                    axios.get('api/blogs/' + query)
                    .then((data) => {
                        this.blogs = data.data
                    })
                    .catch(() => {
                        console.log(error);
                    })
                }
            })

            Fire.$on('AfterCreate', () => {
                this.loadBlog();
            })

            Fire.$on('AfterUpdate', () => {
                this.loadBlog();
            })

            Fire.$on('AfterDelete', () => {
                this.loadBlog();
            })
        },
        mounted() {
            this.loadBlog();
        }
    }
</script>
