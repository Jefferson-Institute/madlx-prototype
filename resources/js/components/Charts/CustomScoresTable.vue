1<template>
  <table class="ttable">
    <tr>
      <th>Users</th>
      <th>Scores</th>
    </tr>
    <tr v-for="c in resultSet" :key="c.name">
      <td>{{ c.name }}</td>
      <td>
        <simple-area-chart :result-set="c.data" :curve="true" />
      </td>
    </tr>
  </table>
</template>

<script>
import SimpleAreaChart from './SimpleAreaChart.vue';

export default {
  name: 'TableChart',
  components: {
    SimpleAreaChart,
  },
  props: {
    resultSet: {
      type: Array,
      required: true,
    },
  },
};
</script>
<style scoped>
  .ttable {
    border-collapse: collapse;
    width: 100%;
  }

  .ttable td, .ttable th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    min-width: 280px;
    text-align: center;
  }
</style>
