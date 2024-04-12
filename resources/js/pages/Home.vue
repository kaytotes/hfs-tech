<template>
<div class="relative flex min-h-screen flex-col overflow-hidden bg-gray- py-6 sm:py-12">
  <div class="mx-auto max-w-screen-xl px-4 w-full">
    <h1 class="mb-10 font-bold text-5xl text-gray-600">Articles</h1>
    <!-- Articles Grid Start -->
    <div class="grid w-full sm:grid-cols-2 xl:grid-cols-4 gap-6">
      <!-- Post Start -->
      <div v-for="article in articles" class="relative flex flex-col shadow-md rounded-xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 max-w-sm">
        <router-link class="z-20 absolute h-full w-full top-0 left-0" :to="{ name: 'Article', params: { article: article.id } }">
          &nbsp;
        </router-link>
        
        <!-- <a href="" class="z-20 absolute h-full w-full top-0 left-0 ">&nbsp;</a> -->

        <div class="bg-white py-4 px-3">
          <h3 class="text-xs mb-2 font-medium">{{ article.title }}</h3>
          <div class="flex justify-between items-center">
            <p class="text-xs text-gray-400">
              {{ article.description_shortened }}
            </p>
          </div>
        </div>
      </div>
      <!-- Post End -->
    </div>
    <!-- Articles Grid End -->
    
    <!-- Pagination Start -->
    <div class="flex justify-center mt-10">
      <nav aria-label="Page Navigation">
        <ul class="flex list-style-none">
          <li class="page-item disabled"><a
              class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-500 pointer-events-none focus:shadow-none"
              href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
          <li class="page-item"><a
              class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
              href="#">1</a></li>
          <li class="page-item active"><a
              class="page-link relative block py-1.5 px-3 rounded border-0 bg-gray-900 outline-none transition-all duration-300 rounded text-white hover:text-white hover:bg-gray-900 shadow-md focus:shadow-md"
              href="#">2 <span class="visually-hidden"></span></a></li>
          <li class="page-item"><a
              class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
              href="#">3</a></li>
          <li class="page-item"><a
              class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
              href="#">Next</a></li>
        </ul>
      </nav>
    </div>
    <!-- Pagination End -->

  </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      articles: [],
      sort: {
        column: 'created_at',
        direction: 'desc',
      },
      links: {
        first: null,
        last: null,
        next: null,
      },
      meta: {
        current_page: null,
        from: null,
        path: null,
        per_page: null,
        to: null,
      }
    }
  },

  created() {
    axios.get(`/api/v1/articles?sort=${this.sort.column}&direction=${this.sort.direction}`)
      .then((response) => {
        this.articles = response.data.data;
        this.links = response.data.links;
        this.meta = response.data.meta;
      });
  }
}
</script>
