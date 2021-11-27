<template>
  <div v-if="result" class="chart-renderer">
    <!-- <component v-if="chartjs.includes(chartType)" :is="componentType" :data="result" height="400px" :donut="chartType==='doughnut'" :xtitle="xtitle" :ytitle="ytitle" :curve="curve" :legend="legend"></component> -->

    <scatter-chart v-if="chartType === 'scatter'" :legend="legend" :result-set="result" :curve="curve" :xtitle="xtitle" :ytitle="ytitle" :url="url" />
    <line-chart v-if="chartType === 'line'" :legend="legend" :result-set="result" :curve="curve" :xtitle="xtitle" :ytitle="ytitle" />
    <line-chart v-if="chartType === 'area'" :legend="legend" :result-set="result" :curve="curve" :xtitle="xtitle" :ytitle="ytitle" :area="true" />
    <stacked-chart v-if="chartType === 'stacked'" :legend="legend" :result-set="result" :xtitle="xtitle" :ytitle="ytitle" />
    <column-chart v-if="chartType === 'column'" :legend="legend" :result-set="result" :xtitle="xtitle" :ytitle="ytitle" :color="color" :inside="inside" />
    <pie-chart v-if="chartType === 'pie'" :legend="legend" :title="title" :result-set="result" />
    <sunburst-chart v-if="chartType === 'sunburst'" :legend="legend" :title="title" :result-set="result" />
    <doughnut-chart v-if="chartType === 'doughnut'" :legend="legend" :title="title" :result-set="result" />
    <rose-chart v-if="chartType === 'rose'" :legend="legend" :title="title" :result-set="result" />
    <Table v-if="chartType === 'table'" :result-set="resultSet" :pivot-config="pivotConfig" :page-size="pageSize" />
    <div v-if="chartType === 'counter'" style="height:200px;max-width:500px;border: 1px solid #f0f2f5;">
      <counter :result-set="resultSet" :pivot-config="pivotConfig" :background="background" :icon="icon" />
    </div>
    <correlogram v-if="chartType === 'correlogram'" :result-set="resultSet" :pivot-config="pivotConfig" />
    <radar-chart v-if="chartType === 'radar'" :result-set="resultSet" :pivot-config="pivotConfig" :legend="legend" />
  </div>
</template>

<script>
import { ResultSet } from '@cubejs-client/core';

import Table from './TableChart.vue';
import Counter from '@/components/Charts/Counter.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import StackedChart from '@/components/Charts/StackedChart.vue';
import ScatterChart from '@/components/Charts/ScatterChart.vue';
import RadarChart from '@/components/Charts/RadarChart.vue';
import ColumnChart from '@/components/Charts/ColumnChart.vue';
import PieChart from '@/components/Charts/PieChart.vue';
import SunburstChart from '@/components/Charts/SunburstChart.vue';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';
import RoseChart from '@/components/Charts/RoseChart.vue';
import Correlogram from '@/components/Charts/Correlogram.vue';

export default {
  name: 'ChartRenderer',

  components: {
    Table,
    Counter,
    LineChart,
    StackedChart,
    ColumnChart,
    PieChart,
    DoughnutChart,
    RoseChart,
    Correlogram,
    ScatterChart,
    RadarChart,
    SunburstChart,
  },
  props: {
    chartType: {
      type: String,
      required: true,
      default: () => 'line',
    },
    resultSet: {
      type: ResultSet,
      required: true,
    },
    pivotConfig: {
      type: Object,
      required: true,
      default() {
        return {};
      },
    },
    icon: {
      type: String,
      required: false,
      default: 'el-icon-s-marketing',
    },
    background: {
      type: String,
      required: false,
    },
    inside: {
      type: Boolean,
      required: false,
    },
    color: {
      type: String,
      required: false,
    },
    title: {
      type: String,
      required: false,
      default: 'Visualisation Title',
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
    url: {
      type: String,
      required: false,
    },
    pageSize: {
      type: Number,
      required: false,
      default: 8,
    },
    redraw: {
      type: Boolean,
      required: false,
      default: true,
    },
  },

  data: () => ({
    result: null,
  }),

  mounted() {
    switch (this.chartType) {
      case 'pie':
      case 'doughnut':
      case 'rose':
        this.result = this.pairs(this.resultSet);
        break;
      case 'bar':
      case 'column':
        this.result = this.seriesPairs(this.resultSet);
        break;
      case 'sunburst':
        this.result = this.tablePivot(this.resultSet);
        break;
      default:
        this.result = this.seriesPairs(this.resultSet);
        break;
    }
  },

  methods: {
    series(resultSet) {
      const seriesNames = resultSet.seriesNames();
      const pivot = resultSet.chartPivot(this.pivotConfig);
      const series = [];

      seriesNames.forEach((e) => {
        const data = pivot.map((p) => [p.x, p[e.key]]);
        series.push({ name: e.key, data });
      });
      return series;
    },
    pairs(resultSet) {
      return resultSet.series(this.pivotConfig)[0].series.map((item) => ({ value: item.value, name: item.x }));
    },
    seriesPairs(resultSet) {
      return resultSet.series(this.pivotConfig).map((seriesItem) => ({
        name: seriesItem.title,
        data: seriesItem.series.map((item) => [item.x, item.value]),
      }));
    },
    tablePivot(resultSet) {
      return resultSet.tablePivot(this.pivotConfig);
    },
  },
};
</script>

<style>
.chart-renderer {
  width: 100%;
}
</style>
