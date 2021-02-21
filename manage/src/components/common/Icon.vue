<template>
  <i :class="'ri-' + icon" aria-hidden="true"></i>
</template>

<script>
    import 'remixicon/fonts/remixicon.css'

  export default {
    name: 'Icon',
    props: {
      icon: {
        type: String,
        required: true,
      },
    },
  }
</script>

