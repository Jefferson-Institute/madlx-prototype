<template>
  <v-chart class="chart" :option="option" :autoresize="true" />
</template>

<script>
import { use } from 'echarts/core';
import { CanvasRenderer } from 'echarts/renderers';
import { LineChart } from 'echarts/charts';
import {
  TooltipComponent,
  LegendComponent,
  GridComponent,
} from 'echarts/components';
import VChart, { THEME_KEY } from 'vue-echarts';

use([
  CanvasRenderer,
  LineChart,
  TooltipComponent,
  LegendComponent,
  GridComponent,
]);

export default {
  name: 'LineChart',
  components: {
    VChart,
  },
  provide: {
    [THEME_KEY]: 'light',
  },
  props: {
    resultSet: {
      type: Array,
      required: true,
    },
    xtitle: {
      type: String,
      required: false,
      default: null,
    },
    ytitle: {
      type: String,
      required: false,
      default: null,
    },
    curve: {
      type: Boolean,
      required: false,
      default: false,
    },
    legend: {
      type: Boolean,
      required: false,
      default: true,
    },
    area: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  computed: {
    option() {
      return {
        color: ['#7AA535', '#35A07C', '#339EA8', '#327CA9', '#305FA1', '#3F3D90', '#453273', '#7AA535', '#35A07C', '#339EA8'],
        tooltip: {
          trigger: 'axis',
        },
        xAxis: {
          type: 'category',
          name: this.xtitle,
          nameLocation: 'middle',
          nameGap: 40,
          nameTextStyle: {
            color: '#303133',
            fontSize: 13,
          },
        },
        yAxis: {
          type: 'value',
          name: this.ytitle,
          nameLocation: 'middle',
          nameGap: 40,
          nameTextStyle: {
            color: '#303133',
            fontSize: 13,
          },
        },
        series: this.resultSet.map(item => ({
          data: item,
          type: 'line',
        })),
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
