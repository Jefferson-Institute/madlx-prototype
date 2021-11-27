<template>
  <div class="app-container">
    <el-row :gutter="10">
      <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
        <el-card class="box-card" style="margin-bottom:10px">
          <div slot="header" class="clearfix">
            <span>LEARNERS({{ learners.length }})</span>
            <div style="float:right">
              <el-button type="success" icon="el-icon-plus" @click="handleCreateLearner">Add New</el-button>
              <el-dropdown @command="handleImport">
                <el-button type="primary" icon="el-icon-upload">
                  Import<i class="el-icon-arrow-down el-icon--right" />
                </el-button>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item command="lrs">From LRS</el-dropdown-item>
                  <el-dropdown-item command="file">From FILE</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <el-button type="primary" icon="el-icon-refresh" @click="refreshData" />
            </div>
          </div>
          <el-table
            :data="learners"
            stripe
            border
            height="700"
            style="width: 100%"
          >
            <el-table-column
              prop="username"
              label="Username"
            />
            <el-table-column
              prop="group"
              label="Group"
            />
            <el-table-column
              prop="country"
              label="Country"
            />
            <el-table-column
              fixed="right"
              label="Operations"
            >
              <template slot-scope="{row}">
                <el-button icon="el-icon-edit" type="success" size="small" @click="handleUpdateLearner(row, row.username)">Edit</el-button>
                <el-button icon="el-icon-delete" type="danger" size="small" @click="handleDeleteLearner(row)">Delete</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </el-col>
      <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
        <el-card class="box-card" style="margin-bottom:10px">
          <div slot="header" class="clearfix">
            <span>GROUPS</span>
            <div style="float:right">
              <el-button type="default" @click="handleCreateGroup">Add New</el-button>
              <el-button type="primary" icon="el-icon-refresh" @click="refreshData" />
            </div>
          </div>
          <el-table
            :data="groups"
            stripe
            border
            style="width: 100%"
          >
            <el-table-column
              prop="name"
              label="Name"
            />
            <el-table-column
              fixed="right"
              label="Operations"
            >
              <template slot-scope="{row}">
                <el-button type="danger" size="small" @click="handleDeleteGroup(row)">Delete</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>COUNTRIES</span>
            <div style="float:right">
              <el-button type="default" @click="handleCreateCountry">Add New</el-button>
              <el-button type="primary" icon="el-icon-refresh" @click="refreshData" />
            </div>
          </div>
          <el-table
            :data="countries"
            stripe
            border
            style="width: 100%"
          >
            <el-table-column
              prop="name"
              label="Name"
            />
            <el-table-column
              fixed="right"
              label="Operations"
            >
              <template slot-scope="{row}">
                <el-button type="danger" size="small" @click="handleDeleteCountry(row)">Delete</el-button>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </el-col>
    </el-row>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="learnerRules" :model="learner" label-position="left">
        <el-form-item label="Username" prop="username">
          <el-input v-model="learner.username" />
        </el-form-item>
        <el-form-item label="Name" prop="name">
          <el-input v-model="learner.name" />
        </el-form-item>
        <el-form-item label="Email" prop="email">
          <el-input v-model="learner.email" />
        </el-form-item>
        <el-form-item label="Bio" prop="bio">
          <el-input v-model="learner.bio" type="textarea" />
        </el-form-item>
        <el-form-item label="Group" prop="group">
          <el-row>
            <el-col>
              <el-select v-model="learner.group" placeholder="please select Group" style="display:block">
                <el-option v-for="item in groups" :key="item.name" :label="item.name" :value="item.name" />
              </el-select>
            </el-col>
          </el-row>
        </el-form-item>
        <el-form-item label="Country" prop="country">
          <el-row>
            <el-col>
              <el-select v-model="learner.country" placeholder="please select Country" style="display:block">
                <el-option v-for="item in countries" :key="item.name" :label="item.name" :value="item.name" />
              </el-select>
            </el-col>
          </el-row>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          Cancel
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createLearnerData():updateLearnerData()">
          Confirm
        </el-button>
      </div>
    </el-dialog>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible1">
      <el-form ref="dataFormGroup" :rules="groupRules" :model="group" label-position="left">
        <el-form-item label="Name" prop="name">
          <el-input v-model="group.name" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible1 = false">
          Cancel
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createGroupData():updateGroupData()">
          Confirm
        </el-button>
      </div>
    </el-dialog>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible2">
      <el-form ref="dataFormCountry" :rules="countryRules" :model="country" label-position="left">
        <el-form-item label="Name" prop="name">
          <el-input v-model="country.name" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible2 = false">
          Cancel
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createCountryData():updateCountryData()">
          Confirm
        </el-button>
      </div>
    </el-dialog>

    <el-dialog title="Import From File" :visible.sync="dialogImportFileVisible">
      <import-learners @learnersImported="refreshData()" />
    </el-dialog>

    <el-dialog title="Import From LRS" :visible.sync="dialogImportLrsVisible">
      <import-learners-lrs @learnersImported="refreshData()" />
    </el-dialog>
  </div>
</template>

<script>
import axios from 'axios';
import importLearners from './components/Import.vue';
import importLearnersLrs from './components/ImportLrs.vue';
export default {
  name: 'Manage',
  components: {
    importLearners,
    importLearnersLrs,
  },
  data() {
    return {
      learner: {
        id: undefined,
        username: undefined,
        name: undefined,
        email: undefined,
        bio: undefined,
        country: undefined,
        group: undefined,
      },
      learnerRules: {
        username: [{ required: true, message: 'Username is required', trigger: 'blur' }],
      },
      learners: [],
      groups: [],
      group: {
        name: undefined,
      },
      groupRules: {
        name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
      },
      countries: [],
      country: {
        name: undefined,
      },
      countryRules: {
        name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
      },
      dialogFormVisible: false,
      dialogFormVisible1: false,
      dialogFormVisible2: false,
      dialogImportFileVisible: false,
      dialogImportLrsVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Edit',
        create: 'Add New',
      },
    };
  },
  created() {
    this.refreshData();
  },
  methods: {
    async getLearners() {
      await axios.get('/api/learners', {
      }).then(response => {
        this.learners = response.data.data;
      }).then(() => {
      });
    },
    async getGroups() {
      await axios.get('/api/learner_groups', {
      }).then(response => {
        this.groups = response.data.data;
      }).then(() => {
      });
    },
    async getCountries() {
      await axios.get('/api/learner_countries', {
      }).then(response => {
        this.countries = response.data.data;
      }).then(() => {
      });
    },
    refreshData() {
      this.getLearners();
      this.getGroups();
      this.getCountries();
    },
    handleCreateLearner() {
      this.resetLearner();
      this.dialogStatus = 'create';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    handleCreateGroup() {
      this.resetGroup();
      this.dialogStatus = 'create';
      this.dialogFormVisible1 = true;
      this.$nextTick(() => {
        this.$refs['dataFormGroup'].clearValidate();
      });
    },
    handleCreateCountry() {
      this.resetCountry();
      this.dialogStatus = 'create';
      this.dialogFormVisible2 = true;
      this.$nextTick(() => {
        this.$refs['dataFormCountry'].clearValidate();
      });
    },
    handleImport(command) {
      this.resetLearner();
      if (command === 'lrs') {
        this.dialogImportLrsVisible = true;
        return;
      }
      this.dialogImportFileVisible = true;
    },
    handleUpdateLearner(row, username) {
      this.learner = Object.assign({}, row); // copy obj
      this.learner.id = username;
      this.dialogStatus = 'update';
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate();
      });
    },
    handleDeleteLearner(row) {
      axios.delete('/api/learners/' + row.username).then(response => {
        console.log(response);
        const index = this.learners.indexOf(row);
        this.learners.splice(index, 1);
        this.$notify({
          title: 'Success',
          message: 'Deleted successfully',
          type: 'success',
          duration: 2000,
        });
      }).catch(error => {
        this.$notify({
          title: 'Error',
          message: error,
          type: 'error',
          duration: 2000,
        });
      });
    },
    handleDeleteGroup(row) {
      axios.delete('/api/learner_groups/' + row.name).then(response => {
        console.log(response);
        const index = this.groups.indexOf(row);
        this.groups.splice(index, 1);
        this.$notify({
          title: 'Success',
          message: 'Deleted successfully',
          type: 'success',
          duration: 2000,
        });
      }).catch(error => {
        this.$notify({
          title: 'Error',
          message: error,
          type: 'error',
          duration: 2000,
        });
      });
    },
    handleDeleteCountry(row) {
      axios.delete('/api/learner_countries/' + row.name).then(response => {
        console.log(response);
        const index = this.countries.indexOf(row);
        this.countries.splice(index, 1);
        this.$notify({
          title: 'Success',
          message: 'Deleted successfully',
          type: 'success',
          duration: 2000,
        });
      }).catch(error => {
        this.$notify({
          title: 'Error',
          message: error,
          type: 'error',
          duration: 2000,
        });
      });
    },
    resetLearner() {
      this.learner = {
        id: undefined,
        username: undefined,
        name: undefined,
        email: undefined,
        bio: undefined,
        country: undefined,
        group: undefined,
      };
    },
    resetGroup() {
      this.group = {
        name: undefined,
      };
    },
    resetCountry() {
      this.country = {
        name: undefined,
      };
    },
    createLearnerData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          axios.post('/api/learners', { ...this.learner }).then(response => {
            console.log(response);
            this.learners.unshift(this.learner);
            this.dialogFormVisible = false;
            this.$notify({
              title: 'Success',
              message: 'Created successfully',
              type: 'success',
              duration: 2000,
            });
          }).catch(error => {
            this.$notify({
              title: 'Error',
              message: error,
              type: 'error',
              duration: 2000,
            });
          });
        }
      });
    },
    createGroupData() {
      this.$refs['dataFormGroup'].validate((valid) => {
        if (valid) {
          axios.post('/api/learner_groups', { ...this.group }).then(response => {
            console.log(response);
            this.groups.unshift(this.group);
            this.dialogFormVisible1 = false;
            this.$notify({
              title: 'Success',
              message: 'Created successfully',
              type: 'success',
              duration: 2000,
            });
          }).catch(error => {
            this.$notify({
              title: 'Error',
              message: error,
              type: 'error',
              duration: 2000,
            });
          });
        }
      });
    },
    createCountryData() {
      this.$refs['dataFormCountry'].validate((valid) => {
        if (valid) {
          axios.post('/api/learner_countries', { ...this.country }).then(response => {
            console.log(response);
            this.countries.unshift(this.country);
            this.dialogFormVisible2 = false;
            this.$notify({
              title: 'Success',
              message: 'Created successfully',
              type: 'success',
              duration: 2000,
            });
          }).catch(error => {
            this.$notify({
              title: 'Error',
              message: error,
              type: 'error',
              duration: 2000,
            });
          });
        }
      });
    },
    updateLearnerData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const learnerData = Object.assign({}, this.learner);
          axios.put('/api/learners/' + learnerData.id, { ...learnerData }).then(response => {
            for (const v of this.learners) {
              if (v.username === this.learner.id) {
                const index = this.learners.indexOf(v);
                this.learners.splice(index, 1, this.learner);
                break;
              }
            }
            this.dialogFormVisible = false;
            this.$notify({
              title: 'Success',
              message: 'Updated successfully',
              type: 'success',
              duration: 2000,
            });
          }).catch(error => {
            this.$notify({
              title: 'Error',
              message: error,
              type: 'error',
              duration: 2000,
            });
          });
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
