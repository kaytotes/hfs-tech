<template>
<div class="relative flex min-h-screen flex-col overflow-hidden bg-gray- py-6 sm:py-12">
  <div class="mx-auto max-w-screen-xl px-4 w-full">
    <h1 class="mb-2 font-bold text-5xl text-gray-600">{{ article.title }}</h1>
    <p class="text-gray-400"><span class="text-xl text-gray-500 font-bold mr-5">{{ article.author.name }}</span>{{ article.created_at }}</p>
    <p class="mt-5 text-gray-600">
      {{ article.description }}
    </p>
  </div>

  <div class="mx-auto max-w-screen-xl mt-5 px-4 w-full">
    <h1 class="mb-2 font-bold text-xl text-gray-600">Comments</h1>
    <div v-for="comment in comments" class="relative w-full mt-5">
      <div>
        <h4 class="font-bold text-gray-900">{{ comment.user_details.name }}</h4>
        <p class="mt-2 max-w-screen-sm text-sm text-gray-500">{{ comment.body }}.</p>
        <span class="mt-1 block text-sm font-semibold text-gray-700">{{ comment.created_at }}</span>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      article: {
        title: '',
        description: '',
        created_at: '',
        author: {
          name: '',
        },
      },
      sort: {
        column: 'created_at',
        direction: 'desc',
      },
      comments: [],
    }
  },

  created() {
    axios.get(`/api/v1/articles/${this.$route.params.article}`)
      .then((response) => {
          this.article = response.data.data;

          axios.get(`/api/v1/articles/${this.$route.params.article}/comments?sort=${this.sort.column}&direction=${this.sort.direction}`)
            .then((response) => {
                console.log(response.data.data);
                this.comments = response.data.data;
            });
      });
  }
}
</script>
    