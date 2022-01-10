<template>
  <div class="dashboard-editor-container">
    <learners />
  </div>
</template>

<script>
import learners from './components/Learners.vue';

export default {
  name: 'LearnersDashboard',
  components: {
    learners,
  },
};
</script>
