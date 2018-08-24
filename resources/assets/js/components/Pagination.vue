<template>
    <div>
        <ul class="pagination pagination-sm m-0 float-left">
            <p> {{pagination.from}} - {{pagination.to}} of {{pagination.total}} </p>
        </ul>
        <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item" v-if="pagination.prevPageUrl"><a class="page-link" href="#" @click="$emit('prev');">Previous</a></li>
            <li class="page-item" v-else :disabled="true"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item" v-if="pagination.nextPageUrl"><a class="page-link" href="#" @click="$emit('next');">Next</a></li>
            <li class="page-item" v-else :disabled="true"><a class="page-link" href="#">Next</a></li>
        </ul>
    </div>
</template>

<script>
export default {
  props: ["pagination"]
};
</script>