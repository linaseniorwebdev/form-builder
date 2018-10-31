<template>
  <div>
    <h2>Draggable Wrapper</h2>
    <draggable v-model="exampleList">
      <div v-for="text in exampleList" :key="text"></div>
    </draggable>
  </div>
</template>

<script>
import draggable from 'vuedraggable';

export default {
  components: {
    draggable
  },

  data() {
    return {
      exampleList: [
        'Item 1',
        'Item 2',
        'Item 3',
        'Item 4',
        'Item 5'
      ]
    }
  }
}
</script>