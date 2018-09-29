<template>
    <div class="info-box">
            <span class="info-box-icon bg-info elevation-1">
                <i class="fa fa-users"></i>
            </span>
        <div class="info-box-content">
            <span class="info-box-text">
                Total Users
                <i @click="getUserCount()" class="fa fa-sync"></i>
            </span>
            <span class="info-box-number" v-if="loadingUserCount">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            <span class="info-box-number" v-else>
                {{ userCount.toLocaleString('en') }}
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
            this.getUserCount();
        },

        data() {
            return {
                userCount: 0,
                loadingUserCount: true,
            };
        },

        methods: {
            getUserCount() {
                this.loadingUserCount = true;
                axios.get("api/count/users")
                .then(({ data }) => {
                    this.userCount = data;
                    this.loadingUserCount = false;
                });
            },
        }
    };
</script>