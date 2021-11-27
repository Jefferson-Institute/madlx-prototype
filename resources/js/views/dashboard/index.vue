<template>
  <div>
    <el-row>
      <el-col :xs="24">
        <div v-loading="loading" element-loading-background="rgba(217, 217, 217, 0.6)" style="background: white;padding: 16px;border-bottom: 1px solid #d8dce5;">
          <el-button-group style="display:flex">
            <el-button v-if="authorities.length > 1" :type="authority == 'combined' ? 'primary' : 'default'" @click="setAuthority('combined')">OVERVIEW</el-button>
            <el-button v-for="a in authorities" :key="a" :type="authority == a ? 'primary' : 'default'" @click="setAuthority(a)">{{ a.toUpperCase() }}</el-button>
          </el-button-group>
        </div>
      </el-col>
    </el-row>
    <div v-if="authorities.length > 1 && authority === 'combined'">
      <combined :authorities="authorities" />
    </div>
    <div v-else>
      <grid-layout
        v-if="visualizations.length > 0"
        :layout.sync="visualizations"
        :col-num="12"
        :row-height="20"
        :vertical-compact="true"
        :use-css-transforms="true"
        @layout-updated="layoutUpdatedEvent"
      >
        <grid-item
          v-for="(viz, index) in visualizations"
          :key="viz.i"
          :x="viz.x"
          :y="viz.y"
          :w="viz.w"
          :h="viz.h"
          :i="viz.i"
          drag-allow-from=".vue-draggable-handle"
          style="touch-action: none"
          @resized="resizedEvent"
          @moved="movedEvent"
        >
          <query-renderer :cubejs-api="cubejsApi" :query="JSON.parse(viz.viz_state)" style="height:100%">

            <template v-slot="{ resultSet }">
              <el-card v-if="viz.type !== 'counter'" class="box-card" style="height:100%" shadow="never">
                <div slot="header" class="clearfix vue-draggable-handle">
                  <span style="text-transform: uppercase; line-height: 1.4;">{{ viz.title }}</span>
                  <el-dropdown trigger="click" style="cursor: pointer;float: right; padding: 3px 0">
                    <span class="el-dropdown-link">
                      <i class="el-icon-more el-icon--right" />
                    </span>
                    <el-dropdown-menu slot="dropdown">
                      <router-link :to="'/edit-visualization/'+viz.id+'/'">
                        <el-dropdown-item icon="el-icon-edit">Edit</el-dropdown-item>
                      </router-link>
                      <el-dropdown-item icon="el-icon-delete" @click.native="deleteVisualization(viz.id, index)">Delete</el-dropdown-item>
                    </el-dropdown-menu>
                  </el-dropdown>
                </div>
                <div v-loading="!resultSet" element-loading-background="transparent" style="height:100%">
                  <div v-if="resultSet" style="height:100%">
                    <ChartRenderer
                      :chart-type="viz.type"
                      :pivot-config="JSON.parse(viz.pivot)"
                      :legend="!!viz.legend"
                      :title="viz.title"
                      :icon="viz.icon"
                      :background="viz.background"
                      :result-set="resultSet"
                      :xtitle="viz.xtitle"
                      :ytitle="viz.ytitle"
                      :curve="!!viz.curve"
                    />
                  </div>
                </div>

              </el-card>
              <div v-if="viz.type === 'counter'" v-loading="!resultSet" element-loading-background="transparent" style="height:100%;background:#ffffff">
                <div v-if="resultSet" style="height:100%">
                  <el-dropdown trigger="click" style="cursor: pointer;position: absolute;right: 6px;padding: 3px 0">
                    <span class="el-dropdown-link">
                      <i class="el-icon-more el-icon--right" />
                    </span>
                    <el-dropdown-menu slot="dropdown">
                      <router-link :to="'/edit-visualization/'+viz.id+'/'">
                        <el-dropdown-item icon="el-icon-edit">Edit</el-dropdown-item>
                      </router-link>
                      <el-dropdown-item icon="el-icon-delete" @click.native="deleteVisualization(viz.id, index)">Delete</el-dropdown-item>
                    </el-dropdown-menu>
                  </el-dropdown>
                  <counter :pivot-config="JSON.parse(viz.pivot)" :title="viz.title" :icon="viz.icon" :background="viz.background" :result-set="resultSet" class="vue-draggable-handle" />
                </div>
              </div>
            </template>
          </query-renderer>
        </grid-item>
      </grid-layout>
      <div v-if="visualizationsLoading" v-loading="visualizationsLoading" element-loading-background="rgba(0, 0, 0, 0)" style="height: 10vh;" />
      <div v-if="!visualizations.length && !visualizationsLoading" style="height: 10vh;">
        <div style="background: rgb(255, 255, 255);border: 1px solid rgb(216, 220, 229);padding: 120px 10px;text-align: center;width: 80%;border-radius: 4px;margin: 40px auto;">
          <i class="el-icon-magic-stick" style="font-size: 96px;color: #d8dce5;" />
          <h3>This dashboard is empty</h3>
          <p style="margin-bottom:30px">Add visualisations to explore your data and get relevent insights.</p>
          <div>
            <router-link :to="'/visualise'">
              <el-button type="primary" small>Add visualisation</el-button>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const visualizationResource = new Resource('visualizations');

import cubejs from '@cubejs-client/core';
import { QueryRenderer } from '@cubejs-client/vue';
import ChartRenderer from '@/components/Charts/ChartRenderer';
import Counter from '@/components/Charts/Counter.vue';
import combined from './combined.vue';
import axios from 'axios';

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
    Counter,
    combined,
  },
  data() {
    return {
      usersQuery: {},
      cubejsApi,
      visualizations: [],
      visualizationsLoading: true,
      draggable: true,
      resizable: true,
      responsive: true,
      authority: null,
      authorities: [],
      learners: [],
      loading: true,
      alowLayoutUpdate: false,
    };
  },
  created() {
    // fetch data sources
    this.getAuthorities().then(() => {
      this.getLearners().then(() => {
        this.getVisualizations();
      });
    });
  },
  methods: {
    globalFilterAndPrepareState(state) {
      const parsedState = JSON.parse(state);
      if (parsedState.filters.filter(function(f) {
        return f.member === 'Statements.actorMbox' && f.operator === 'equals';
      }).length > 0) {
        parsedState.filters.map(f => {
          if (f.member === 'Statements.actorMbox' && f.operator === 'equals') {
            f.values.push(...this.learners);
          }
        });
      } else {
        parsedState.filters.push({
          member: 'Statements.actorMbox',
          operator: 'equals',
          values: this.learners,
        });
      }

      return parsedState;
    },
    async getLearners() {
      return await axios.get('/api/learners', {
      }).then(response => {
        this.learners = response.data.data.map(l => l.email);
      });
    },
    async getVisualizations() {
      this.visualizationsLoading = true;
      const { data } = await visualizationResource.list({ authority: this.authority, dashboard_id: this.$route.query.d_id });
      this.visualizations = data.map(x => {
        return { ...x, i: x.id };
      });
      this.visualizationsLoading = false;
    },
    async deleteVisualization(id, index) {
      const { data } = await visualizationResource.destroy(id, { authority: this.authority, dashboard_id: this.$route.query.d_id });
      if (Array.isArray(data)) {
        this.visualizations = data.map(x => {
          return { ...x, i: x.id };
        });
      } else {
        this.visualizations.splice(index, 1);
      }
      this.$notify({
        title: 'Success',
        message: 'Visualization Removed',
        type: 'success',
        duration: 2000,
      });
    },
    async updateVisualizations(newData) {
      const { data } = await visualizationResource.updateAll({ visualisations: newData, authority: this.authority, dashboard_id: this.$route.query.d_id });
      if (!data.Success) {
        this.visualizations = data.map(x => {
          return { ...x, i: x.id };
        });
      }
      this.$notify({
        title: 'Success',
        message: 'New Positions Saved',
        type: 'success',
        duration: 2000,
      });
    },
    resizedEvent: function(i, newH, newW, newHPx, newWPx){
      this.alowLayoutUpdate = true;
    },
    movedEvent: function(i, newX, newY){
      this.alowLayoutUpdate = true;
    },
    layoutUpdatedEvent: function(newLayout){
      if (this.alowLayoutUpdate) {
        this.updateVisualizations(newLayout);
        this.alowLayoutUpdate = false;
      }
    },
    async getAuthorities() {
      const response = await axios.get('/api/authorities', {});
      this.authorities = response.data.data;
      if (response.data.data.length > 1) {
        this.authority = 'combined';
      } else {
        this.authority = response.data.data[0];
      }
      this.loading = false;
    },
    setAuthority(value) {
      this.visualizations = [];
      this.authority = value;
      this.getVisualizations();
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .vue-grid-layout {
    overflow: hidden;
    max-width:100%;
  }
</style>
