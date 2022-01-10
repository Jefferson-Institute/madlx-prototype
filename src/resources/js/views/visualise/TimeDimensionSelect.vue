<template>
  <el-select :clearable="true" :value="timeDimensions[0] && timeDimensions[0].dimension.name" placeholder="Time" :style="{width: '100%', 'margin-bottom': '10px'}" @change="handleTimeChange">
    <el-option
      v-for="item in availableTimeDimensions"
      :key="item.name"
      :label="item.shortTitle"
      :value="item.name"
    />
  </el-select>
</template>

<script>
export default {
  name: 'TimeDimensionSelectVue',
  props: ['timeDimensions', 'availableTimeDimensions'],
  methods: {
    handleTimeChange(value) {
      const [selectedTd = {}] = this.timeDimensions;
      const td = this.availableTimeDimensions.find(({ name }) => name === value);
      if (!td) {
        this.$emit('change', []);
        return;
      }
      this.$emit('change', [
        {
          dimension: td.name,
          granularity: selectedTd.granularity || 'day',
          dateRange: selectedTd.dateRange,
        },
      ]);
    },
  },
};
</script>

<style scoped>
</style>
