<template>
  <v-chart class="chart" :option="option" :autoresize="true" />
</template>

<script>
import { use } from 'echarts/core';
import { CanvasRenderer } from 'echarts/renderers';
import { RadarChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';

use([
  CanvasRenderer,
  RadarChart,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
]);

export default {
  name: 'RadarChart',
  components: {
    VChart,
  },
  provide: {
    [THEME_KEY]: 'light',
  },
  props: {
    resultSet: {
      type: Object,
      required: true,
    },
    legend: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  computed: {
    option() {
      // how data should come in
      // {
      //     "Jefferson Education": {
      //         "Group 1": 79.44333333333333,
      //         "Group 2": 42.5,
      //         "Control group": 81.66624999999999
      //     },
      //     "Observers": {
      //         "Group 1": 65.2,
      //         "Group 2": 58,
      //         "Control group": 66.56666666666666
      //     }
      // }

      const final = [];
      let indicators = [];
      let values = [];
      let max = 0;
      for (const property in this.resultSet) {
        values = values.concat(Object.values(this.resultSet[property]));
        max = Math.max(...values);
        final.push({ name: property, value: Object.values(this.resultSet[property]) });
        indicators = Object.keys(this.resultSet[property]).map(i => ({ name: i, max: max }));
      }

      return {
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              title: 'Save',
              show: true,
            },
          },
        },
        color: ['#7AA535', '#35A07C', '#339EA8', '#327CA9', '#305FA1', '#3F3D90', '#453273', '#7AA535', '#35A07C', '#339EA8'],
        tooltip: {
          trigger: 'item',
        },
        legend: {
          data: final.map(i => i.name),
        },
        radar: {
          indicator: indicators,
          center: ['50%', '60%'],
        },
        series: [{
          type: 'radar',
          areaStyle: {},
          data: final,
        }],
      };
    },
  },
};
</script>

<style scoped>
.chart {
  height: 400px;
}
</style>
