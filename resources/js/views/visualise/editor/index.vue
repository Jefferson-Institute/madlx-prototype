<template>
  <div v-loading="loading" class="dashboard-editor-container" element-loading-background="rgba(217, 217, 217, 0.6)">
    <h2 v-if="editMode" style="text-align: center;margin-top: 0;margin-bottom: 30px;">EDIT MODE</h2>

    <el-row :gutter="20">
      <el-col :sm="6">
        <p style="margin: 0 2px 4px;color: #606266c4;">Dashboard:</p>
        <el-select :value="authority" placeholder="Data Source" :style="{width: '100%', 'margin-bottom': '10px'}" @change="setAuthority">
          <el-option
            label="COMBINED"
            :value="'combined'"
          />
          <el-option
            v-for="a in authorities"
            :key="a"
            :label="a.toUpperCase()"
            :value="a"
          />
        </el-select>
      </el-col>
    </el-row>

    <hr
      style="border: none;
      height: 1px;
      background: #d8dce5;
      margin: 25px 0;"
    >

    <query-builder v-if="showQueryBuilder" ref="qb" :cubejs-api="cubejsApi" :query="query" :disable-heuristics="true" style="width: 100%">
      <template
        v-slot:builder="{
          measures,
          setMeasures,
          availableMeasures,
          dimensions,
          setDimensions,
          availableDimensions,
          timeDimensions,
          setTimeDimensions,
          availableTimeDimensions,
          validatedQuery,
          pivotConfig,
          updatePivotConfig,
          filters,
          setFilters,
          limit,
          setLimit,
          orderMembers,
          updateOrder,
          isQueryPresent,
        }"
      >
        <el-row :gutter="20">
          <el-col :sm="6">
            <el-select :value="measures.map(i => (i.name))" placeholder="Measures" multiple :style="{width: '100%', 'margin-bottom': '10px'}" @change="setMeasures">
              <el-option
                v-for="item in availableMeasures"
                :key="item.name"
                :label="item.title"
                :value="item.name"
              />
            </el-select>
          </el-col>
          <el-col :sm="6">
            <el-select :value="dimensions.map(i => (i.name))" multiple placeholder="Dimensions" :style="{width: '100%', 'margin-bottom': '10px'}" @change="setDimensions">
              <el-option
                v-for="item in availableDimensions.map(i => (i.name))"
                :key="item"
                :label="item"
                :value="item"
              />
            </el-select>
          </el-col>
          <el-col :sm="4">
            <TimeDimensionSelect
              :available-time-dimensions="availableTimeDimensions"
              :time-dimensions="timeDimensions"
              @change="setTimeDimensions"
            />
          </el-col>
          <el-col v-if="timeDimensions[0]" :sm="4">
            <el-select :value="timeDimensions[0]?timeDimensions[0]['dateRange']?timeDimensions[0]['dateRange']:'All time':'All time'" placeholder="Date Range" :style="{width: '100%', 'margin-bottom': '10px'}" @change="setTimeDimensions([{dimension: timeDimensions[0]['dimension']['name'], granularity: timeDimensions[0]['granularity']?timeDimensions[0]['granularity']:granularities[1].name, dateRange: $event}])">
              <el-option
                :label="'All time'"
                :value="undefined"
              />
              <el-option
                v-for="item in dateRangeItems"
                :key="item"
                :label="item"
                :value="item"
              />
            </el-select>
          </el-col>
          <el-col v-if="timeDimensions[0]" :sm="4">
            <el-select :value="timeDimensions[0]?timeDimensions[0]['granularity']?timeDimensions[0]['granularity']:granularities[1].name:granularities[1].name" placeholder="Granularity" :style="{width: '100%', 'margin-bottom': '10px'}" @change="setTimeDimensions([{dimension: timeDimensions[0]['dimension']['name'], granularity: $event, dateRange: timeDimensions[0]['dateRange']?timeDimensions[0]['dateRange']:undefined}])">
              <el-option
                v-for="item in granularities"
                :key="item.name"
                :label="item.title"
                :value="item.name"
              />
            </el-select>
          </el-col>
        </el-row>
        <hr
          style="border: none;
          height: 1px;
          background: #d8dce5;
          margin: 25px 0;"
        >
        <el-row :gutter="20">
          <el-col :sm="24">
            <div style="display:flex;">
              <FilterComponent
                :filters="filters"
                :dimensions="availableDimensions.map(i => (i.name))"
                :measures="availableMeasures.map(i => (i.name))"
                :set-filters="setFilters"
                style="margin-right: 20px"
              />
              <Limit :limit="Number(limit)" style="margin-right: 20px" @update="setLimit" />
              <Order
                :order-members="orderMembers"
                :disabled="!isQueryPresent"
                style="margin-right: 20px"
                @orderChange="updateOrder.set"
                @reorder="updateOrder.reorder"
              />
              <PivotConfig
                :pivot-config="pivotConfig"
                :disabled="!isQueryPresent"
                @move="(value) => handleMove(value, updatePivotConfig)"
              />
            </div>
          </el-col>
        </el-row>
        <hr
          style="border: none;
          height: 1px;
          background: #d8dce5;
          margin: 25px 0;"
        >
        <el-row type="flex" justify="space-between">
          <el-col :xs="24">
            <el-select v-model="type" placeholder="Chart Type" style="margin-right:15px">
              <el-option
                v-for="item in ['line', 'area', 'column', 'pie', 'doughnut', 'rose', 'table', 'counter', 'correlogram', 'scatter', 'radar', 'sunburst']"
                :key="item"
                :label="item"
                :value="item"
              />
            </el-select>
            <div style="display: inline-block;vertical-align: middle;margin-right:15px">
              <el-checkbox v-model="legend" label="Show Legend" border size="medium" style="background:#fff" />
            </div>
            <div style="display: inline-block;vertical-align: middle;margin-right:15px">
              <el-checkbox v-model="curve" label="Smooth Lines" border size="medium" style="background:#fff" />
            </div>
            <el-input v-model="xtitle" style="max-width: 150px;display: inline-block;margin-right:15px" placeholder="X Axis Title" />
            <el-input v-model="ytitle" style="max-width: 150px;display: inline-block;margin-right:15px" placeholder="Y Axis Title" />
            <el-select v-if="type==='counter'" v-model="icon" placeholder="Chart Icon" style="margin-right:15px">
              <el-option
                v-for="item in [
                  {key: 'el-icon-s-flag', value: 'Flag'},
                  {key: 'el-icon-s-marketing', value: 'Chart'},
                  {key: 'el-icon-s-data', value: 'Chart 2'},
                  {key: 'el-icon-share', value: 'Share'},
                  {key: 'el-icon-s-tools', value: 'Tools'},
                  {key: 'el-icon-user-solid', value: 'User'},
                  {key: 'el-icon-more', value: 'More'},
                  {key: 'el-icon-star-on', value: 'Star'},
                  {key: 'el-icon-info', value: 'Info'},
                  {key: 'el-icon-s-operation', value: 'Operation'},
                  {key: 'el-icon-s-home', value: 'Home'},
                  {key: 'el-icon-s-comment', value: 'Comment'},
                  {key: 'el-icon-s-opportunity', value: 'Light Bulb'},
                ]"
                :key="item.key"
                :label="item.value"
                :value="item.key"
              />
            </el-select>
            <div v-if="type==='counter'" style="margin-right:15px;background: rgb(255, 255, 255);display: inline-flex;vertical-align: top;font-size: 13px;color: #606266;align-items: center;border: 1px solid #dcdfe6;border-radius: 4px;width: 147px;padding: 1px 15px;justify-content: space-between;">
              <span>Chart Color: </span>
              <el-color-picker v-model="background" size="small" />
            </div>
          </el-col>
          <el-col :xs="24" :sm="6" :md="4">
            <el-button v-if="!editMode" type="primary" :style="{width: '100%'}" @click="addToDashboard(validatedQuery)">Add to Dashboard</el-button>
            <el-button v-if="editMode" type="success" :style="{width: '100%'}" @click="updateVisualization(validatedQuery)">Update Visualization</el-button>
          </el-col>
        </el-row>
      </template>

      <template v-slot="{ resultSet }">
        <div v-if="resultSet" style="padding: 25px;background: #ffffff;margin: 25px 0;border-radius: 5px;overflow-y: auto;">
          <ChartRenderer :pivot-config="pivot_config" :chart-type="type" :result-set="resultSet" :background="background" :icon="icon" :xtitle="xtitle" :ytitle="ytitle" :curve="curve" :legend="legend" />
        </div>
      </template>

      <template v-slot:empty>
        <div
          style="
              margin-top: 160px;
              margin-bottom: 120px;
              color: #43436b;
              text-align:center;
            "
        >
          <div>
            <h3>Build Your Query</h3>
            <p style="color: #969696">Choose a measure or dimension to get started</p>
          </div>
        </div>
      </template>
    </query-builder>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const visualizationResource = new Resource('visualizations');

import axios from 'axios';
import cubejs from '@cubejs-client/core';
import { QueryBuilder } from '@cubejs-client/vue';
import ChartRenderer from '@/components/Charts/ChartRenderer';
import FilterComponent from '../FilterComponent.vue';
import TimeDimensionSelect from '../TimeDimensionSelect.vue';
import Order from '../Order.vue';
import Limit from '../Limit.vue';
import PivotConfig from '../PivotConfig.vue';

const API_URL = 'http://127.0.0.1:4000';
const CUBEJS_TOKEN =
  'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2MTMwNDg1MjMsImV4cCI6MTY0NDU4NDUyNCwiYXVkIjoiZGFzaC5qZWZmZXJzb25ob3N0aW5nLm9yZyIsInN1YiI6IiIsIkVtYWlsIjoiYWRtaW5AamVmZmVyc29uaG9zdGluZy5vcmcifQ.mSfNczOIZyfXXCrU61WCwWVB1ojQCy3WjUy_8WAGnz8';
const cubejsApi = cubejs(CUBEJS_TOKEN, {
  apiUrl: `${API_URL}/cubejs-api/v1`,
});

export default {
  name: 'DashboardAdmin',
  components: {
    TimeDimensionSelect,
    Order,
    Limit,
    PivotConfig,
    QueryBuilder,
    ChartRenderer,
    FilterComponent,
  },
  data: () => {
    const query = {
      limit: 1000,
      measures: [],
      dimensions: [],
      timeDimensions: [],
      filters: [],
      order: [],
    };

    return {
      showQueryBuilder: false,
      editMode: false,
      visualizationBeingEdited: null,
      cubejsApi,
      query,
      dateRangeItems: ['Today', 'Yesterday', 'This week', 'This month', 'This quarter', 'This year', 'Last 7 days', 'Last 30 days', 'Last week', 'Last month', 'Last quarter', 'Last year'],
      granularities: [{ 'name': 'hour', 'title': 'Hour' }, { 'name': 'day', 'title': 'Day' }, { 'name': 'week', 'title': 'Week' }, { 'name': 'month', 'title': 'Month' }, { 'name': 'year', 'title': 'Year' }],
      type: 'line',
      authority: 'combined',
      authorities: [],
      loading: true,
      background: '#FFFFFF',
      icon: null,
      xtitle: null,
      ytitle: null,
      curve: false,
      legend: true,
      pivot_config: {},
    };
  },
  created() {
    // fetch data sources
    this.getAuthorities();

    if (this.$route.params.id) {
      // switch to edit mode
      this.editMode = true;
      this.$notify({
        title: 'Info',
        message: 'You are now in Edit mode.',
        type: 'info',
        duration: 2000,
      });
      // fetch visualisation
      this.getVisualization(this.$route.params.id);
    } else {
      this.showQueryBuilder = true;
    }
  },
  methods: {
    handleMove(value, updatePivotConfig) {
      updatePivotConfig.update(value);
      if (value.x) {
        const obj = {};
        obj.x = value.x;
        obj.y = this.pivot_config.y ? this.pivot_config.y : undefined;
        this.pivot_config = obj;
      }
      if (value.y) {
        const obj = {};
        obj.y = value.y;
        obj.x = this.pivot_config.x ? this.pivot_config.x : undefined;
        this.pivot_config = obj;
      }
    },
    addToDashboard(query) {
      this.$prompt('Please input Chart Title', 'Save Chart', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
      }).then(({ value }) => {
        visualizationResource.store({ query, title: value, type: this.type, pivot: this.pivot_config, background: this.background, authority: this.authority, icon: this.icon, legend: this.legend, xtitle: this.xtitle, ytitle: this.ytitle, curve: this.curve }).then(() => {
          this.$notify({
            title: 'Success',
            message: 'Visualization created successfully',
            type: 'success',
            duration: 2000,
          });
        });
      }).catch(() => {
        this.$notify({
          title: 'Error',
          message: 'Not Created!',
          type: 'info',
          duration: 2000,
        });
      });
    },
    updateVisualization(query) {
      this.$prompt('Update Chart Title', 'Save Chart', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        inputValue: this.visualizationBeingEdited.title,
      }).then(({ value }) => {
        visualizationResource.update(this.visualizationBeingEdited.id, { query, title: value, type: this.type, pivot: this.pivot_config, background: this.background, authority: this.authority, icon: this.icon, legend: this.legend, xtitle: this.xtitle, ytitle: this.ytitle, curve: this.curve }).then(() => {
          this.editMode = false;
          this.visualizationBeingEdited = null;
          this.$notify({
            title: 'Success',
            message: 'Visualization updated successfully',
            type: 'success',
            duration: 2000,
          });
          this.$router.replace('/dashboards');
        });
      }).catch(() => {
        this.$notify({
          title: 'Error',
          message: 'Not Updated!',
          type: 'info',
          duration: 2000,
        });
      });
    },
    async getVisualization(id) {
      const { data } = await visualizationResource.get(id);
      this.authority = data.authority;
      this.visualizationBeingEdited = data;
      this.query = JSON.parse(data.viz_state);
      this.pivot_config = JSON.parse(data.pivot);
      this.type = data.type;
      this.background = data.background;
      this.icon = data.icon;
      this.xtitle = data.xtitle;
      this.ytitle = data.ytitle;
      this.legend = !!data.legend;
      this.curve = !!data.curve;
      this.showQueryBuilder = true;
    },
    getAuthorities() {
      axios.get('/api/authorities', {
      }).then(response => {
        this.authorities = response.data.data;
      }).then(() => {
        this.loading = false;
      });
    },
    setAuthority(value) {
      let filters = this.$refs.qb.filters;
      this.authority = value;

      filters = filters.filter(f => {
        return f.member.name !== 'Statements.authorityName' || f.operator !== 'equals';
      });

      if (value && value !== 'combined') {
        filters = [
          ...filters,
          {
            'member': {
              'name': 'Statements.authorityName',
              'title': 'Statements Authority.name',
              'type': 'string',
              'shortTitle': 'Authority.name',
              'suggestFilterValues': true,
            },
            'operator': 'equals',
            'values': [
              value,
            ],
            'operators': [
              {
                'name': 'contains',
                'title': 'contains',
              },
              {
                'name': 'notContains',
                'title': 'does not contain',
              },
              {
                'name': 'equals',
                'title': 'equals',
              },
              {
                'name': 'notEquals',
                'title': 'does not equal',
              },
              {
                'name': 'set',
                'title': 'is set',
              },
              {
                'name': 'notSet',
                'title': 'is not set',
              },
            ],
            'index': 0,
          },
        ];
      }

      this.$refs.qb.filters = filters;
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}
</style>
