<template>
  <el-container>
    <el-main>
      <el-row :gutter="20">
        <el-col :xs="24">
          <div style="background: rgb(255, 255, 255);color:#2c587c;border: 1px solid #e6ebf5;border-radius:4px;padding: 20px;text-align: center;font-size: 22px;text-transform: uppercase;">
            {{ username }}
            <span style="font-size: 12px;display: block;color: #304156;opacity: 0.5;padding: 4px 0;">
              {{ email }}
            </span>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <grid-layout
          :layout.sync="visualizations"
          :col-num="12"
          :row-height="20"
          :vertical-compact="true"
          :use-css-transforms="true"
        >
          <grid-item
            v-for="(viz, index) in visualizations"
            :key="index + 1"
            :x="viz.x"
            :y="viz.y"
            :w="viz.w"
            :h="viz.h"
            :i="index + 1"
            :is-resizable="false"
            drag-allow-from=".vue-draggable-handle"
            style="touch-action: none"
          >
            <query-renderer :cubejs-api="cubejsApi" :query="viz.viz_state" style="height:100%">

              <template v-slot="{ resultSet }">
                <el-card v-loading="!resultSet" shadow="never" element-loading-background="transparent" class="box-card" style="height:100%">
                  <div slot="header" class="clearfix">
                    <span style="text-transform: uppercase; line-height: 1.4;">{{ viz.title }}</span>
                  </div>
                  <div v-if="resultSet" style="height:100%">
                    <ChartRenderer :chart-type="viz.type" :curve="viz.curve" :pivot-config="viz.pivot" :legend="!!viz.legend" :title="viz.title" :icon="viz.icon" :background="viz.background" :result-set="resultSet" />
                  </div>
                </el-card>
              </template>
            </query-renderer>
          </grid-item>
        </grid-layout>
        <div v-if="visualizationsLoading" v-loading="visualizationsLoading" element-loading-background="rgba(0, 0, 0, 0)" style="height: 10vh;" />
      </el-row>
    </el-main>
  </el-container>
</template>
<script>
import cubejs from '@cubejs-client/core';
import ChartRenderer from '@/components/Charts/ChartRenderer';
import { QueryRenderer } from '@cubejs-client/vue';

import { GridLayout, GridItem } from 'vue-grid-layout';

const API_URL = 'https://demo-cube.madlx.org';
const CUBEJS_TOKEN =
  'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmxpbmUgSldUIEJ1aWxkZXIiLCJpYXQiOjE2MTMwNDg1MjMsImV4cCI6MTY0NDU4NDUyNCwiYXVkIjoiZGFzaC5qZWZmZXJzb25ob3N0aW5nLm9yZyIsInN1YiI6IiIsIkVtYWlsIjoiYWRtaW5AamVmZmVyc29uaG9zdGluZy5vcmcifQ.mSfNczOIZyfXXCrU61WCwWVB1ojQCy3WjUy_8WAGnz8';
const cubejsApi = cubejs(CUBEJS_TOKEN, {
  apiUrl: `${API_URL}/cubejs-api/v1`,
});

export default {
  name: 'Dashboard',
  components: {
    QueryRenderer,
    ChartRenderer,
    GridLayout,
    GridItem,
  },
  data() {
    return {
      usersQuery: {},
      cubejsApi,
      visualizations: [],
      visualizationsLoading: true,
      email: undefined,
      username: undefined,
    };
  },
  created() {
    if (this.$route.params.email) {
      this.email = this.$route.params.email;
      this.getVisualization();
    }

    if (this.$route.params.username) {
      this.username = this.$route.params.username;
    }
  },
  methods: {
    async getVisualization() {
      this.visualizationsLoading = true;
      this.visualizations = [
        {
          'i': 'leave this',
          'background': '#FFFFFF',
          'icon': null,
          'xtitle': null,
          'ytitle': null,
          'legend': 1,
          'viz_state': { 'measures': ['Statements.coursesAVGScore'], 'dimensions': ['Statements.authorityName'], 'filters': [{ 'member': 'Statements.actorMbox', 'operator': 'equals', 'values': [this.email] }], 'limit': 1000, 'order': [] },
          'pivot': {},
          'title': 'Average score by Source',
          'type': 'column',
          'x': 0,
          'y': 0,
          'w': 6,
          'h': 17,
        },
        {
          'i': 'leave this',
          'background': '#FFFFFF',
          'icon': null,
          'xtitle': null,
          'ytitle': null,
          'legend': 1,
          'viz_state': { 'measures': ['Statements.ScoreAll'], 'dimensions': ['Statements.objectDefinitionNameUnd'], 'filters': [{ 'member': 'Statements.actorMbox', 'operator': 'equals', 'values': [this.email] }, { 'member': 'Statements.objectDefinitionNameUnd', 'operator': 'set', 'values': [''] }, { 'member': 'Statements.resultScoreScaled', 'operator': 'set', 'values': [''] }], 'limit': 1000, 'order': [] },
          'pivot': {},
          'title': 'Average score by course',
          'type': 'pie',
          'x': 6,
          'y': 0,
          'w': 6,
          'h': 17,
        },
        {
          'i': 'leave this',
          'background': '#FFFFFF',
          'icon': null,
          'xtitle': null,
          'ytitle': null,
          'legend': 0,
          'viz_state': { 'measures': ['Statements.count'], 'dimensions': ['Statements.contextContextActivitiesParent', 'Statements.objectDefinitionNameUnd'], 'timeDimensions': [{ 'dimension': 'Statements.timestamp', 'granularity': 'day' }], 'filters': [{ 'member': 'Statements.actorMbox', 'operator': 'equals', 'values': [this.email] }], 'limit': 10000, 'order': [] },
          'pivot': { 'x': ['Statements.timestamp.day'], 'y': ['Statements.contextContextActivitiesParent', 'Statements.objectDefinitionNameUnd', 'measures'] },
          'title': 'Learning activities timeline',
          'type': 'area',
          'curve': true,
          'x': 0,
          'y': 18,
          'w': 12,
          'h': 17,
        },
        {
          'i': 'leave this',
          'background': '#FFFFFF',
          'icon': null,
          'xtitle': null,
          'ytitle': null,
          'legend': 1,
          'viz_state': { 'dimensions': ['Statements.id', 'Statements.verbDisplayEn', 'Statements.objectDefinitionNameUnd', 'Statements.contextContextActivitiesParent'], 'timeDimensions': [{ 'dimension': 'Statements.timestamp', 'granularity': 'day' }], 'limit': 1000, 'order': [] },
          'pivot': {},
          'title': 'Verbs timeline',
          'type': 'table',
          'x': 0,
          'y': 36,
          'w': 9,
          'h': 17,
        },
        {
          'i': 'leave this',
          'background': '#FFFFFF',
          'icon': null,
          'xtitle': null,
          'ytitle': null,
          'legend': 1,
          'viz_state': { 'measures': ['Statements.ScoreAll'], 'dimensions': ['Statements.objectDefinitionNameUnd'], 'filters': [{ 'member': 'Statements.resultScoreScaled', 'operator': 'set', 'values': [''] }, { 'member': 'Statements.objectDefinitionNameUnd', 'operator': 'set', 'values': [''] }], 'limit': 1000, 'order': [] },
          'pivot': {},
          'title': 'Course Results',
          'type': 'table',
          'x': 9,
          'y': 36,
          'w': 3,
          'h': 17,
        },
      ];
      this.visualizationsLoading = false;
    },
  },
};
</script>
