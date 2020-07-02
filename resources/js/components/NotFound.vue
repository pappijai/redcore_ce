<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="error-page mt-4">
                <h2 class="headline text-teal"> 404</h2>

                <div class="error-content">
                <h3><i class="fa fa-warning text-warning"></i> Oops! Page not found.</h3>

                <p>
                    We could not find the page you were looking for.
                </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
