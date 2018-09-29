<template>
    <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1">
            <i class="fa fa-user-plus"></i>
        </span>
        <div class="info-box-content">
            <span class="info-box-text">
                New Users
                <i @click="getNewUserCount()" class="fa fa-sync"></i>
            </span>
            <span class="info-box-number" v-if="loadingNewUserCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ newUserCount.toLocaleString('en') }}
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Component mounted.')
        },

        created() {
            this.getNewUserCount();
        },

        data() {
            return {
                newUserCount: 0,
                loadingNewUserCount: true,
            };
        },

        methods: {
            getNewUserCount() {
                this.loadingNewUserCount = true;
                axios.get("api/count/users/new")
                    .then(({ data }) => {
                        this.newUserCount = data;
                        this.loadingNewUserCount = false;
                });
            },
        }
    };
</script>