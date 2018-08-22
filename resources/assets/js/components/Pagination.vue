<template>
    <div v-if="!client">
        <ul class="pagination pagination-sm m-0 float-left">
            <p> {{pagination.from}} - {{pagination.to}} of {{pagination.total}} </p>
        </ul>
        <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item" v-if="pagination.prevPageUrl"><a class="page-link" href="#" @click="$emit('prev');">{{pagination.from}}</a></li>
            <li class="page-item" v-else :disabled="true"><a class="page-link" href="#">{{pagination.from}}</a></li>
            <li class="page-item" v-if="pagination.nextPageUrl"><a class="page-link" href="#" @click="$emit('next');">{{pagination.to}}</a></li>
            <li class="page-item" v-else :disabled="true"><a class="page-link" href="#">{{pagination.to}}</a></li>
        </ul>
    </div>
     <div v-else>
        <ul class="pagination pagination-sm m-0 float-left">
            <p> {{pagination.from}} - {{pagination.to}} of {{filtered.length}} </p>
            <p> Filtered from {{pagination.total}} total entries.</p>
        </ul>
        <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item" v-if="pagination.prevPage"><a class="page-link" href="#" @click="$emit('prev');">{{pagination.from}}</a></li>
            <li class="page-item" v-else :disabled="true"><a class="page-link" href="#">{{pagination.from}}</a></li>
            <li class="page-item" v-if="pagination.nextPage"><a class="page-link" href="#" @click="$emit('next');">{{pagination.to}}</a></li>
            <li class="page-item" v-else :disabled="true"><a class="page-link" href="#">{{pagination.to}}</a></li>
        </ul>
    </div>
</template>

<script>
export default {
  props: ["pagination", "client", "filtered"]
};
</script>