<template>
  <div class="app-container">
    <el-row type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="12">
        <el-steps :active="active_step" finish-status="success" align-center style="margin:20px 0">
          <el-step title="Step 1" description="Import Data" />
          <el-step title="Step 2" description="Map Data to Template" />
          <el-step title="Step 3" description="Set Source Name" />
        </el-steps>
      </el-col>
    </el-row>
    <el-row v-if="active_step === 0" type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="12">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;">
            <span><strong>Step 1</strong> - Import Data</span>
            <el-button type="primary" style="float: right;" @click="nextStep">Next step <i class="el-icon-arrow-right" /></el-button>
          </div>
          <h5>Upload CSV:</h5>
          <input id="csv_file" type="file" name="csv_file" class="form-control" @change="loadCSV($event)">
          <div class="separator">OR</div>
          <h5>Paste CSV:</h5>
          <el-input
            v-model="pasted_csv"
            type="textarea"
            :rows="8"
          />
        </el-card>
      </el-col>
    </el-row>
    <el-row v-if="active_step === 1" type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="12">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;">
            <el-button plain style="float: right;" icon="el-icon-arrow-left" @click="prevStep">Previous step</el-button>
            <span><strong>Step 2</strong> - Map CSV Data to Template</span>
            <el-button type="primary" style="float: right;" @click="nextStep">Next step <i class="el-icon-arrow-right" /></el-button>
          </div>
          <div v-if="parse_header.length > 0" style="margin-bottom:25px">
            <h5 style="margin:0 0 10px">Available Wildcards:</h5>
            <el-button v-for="h in parse_header" :key="h" v-clipboard="'{{'+h+'}}'" type="success" plain size="small" round @success="copiedToClipboard()">{{ h }}</el-button>
          </div>
          <div style="margin-bottom:25px">
            <h5 style="margin:0 0 10px">Select Template:</h5>
            <el-select v-model="active_template" placeholder="Select" @change="loadTemplate">
              <el-option
                v-for="item in template_options"
                :key="item"
                :label="item"
                :value="item"
              />
            </el-select>
          </div>
          <el-collapse v-model="activeStatementOptions" style="margin-bottom:25px">
            <el-collapse-item name="actor">
              <template slot="title">
                Actor <a href="https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Data.md#actor" target="_blank" style="cursor: help;position: absolute;right: 28px;color: #3e90ff;font-size: 23px;"><i class="el-icon-question" /></a>
              </template>
              <el-input
                v-model="template.actor"
                type="textarea"
                autosize
                @change="parseTemplate()"
              />
            </el-collapse-item>
            <el-collapse-item name="verb">
              <template slot="title">
                Verb <a href="https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Data.md#verb" target="_blank" style="cursor: help;position: absolute;right: 28px;color: #3e90ff;font-size: 23px;"><i class="el-icon-question" /></a>
              </template>
              <el-input
                v-model="template.verb"
                type="textarea"
                autosize
                @change="parseTemplate()"
              />
            </el-collapse-item>
            <el-collapse-item name="object">
              <template slot="title">
                Object <a href="https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Data.md#object" target="_blank" style="cursor: help;position: absolute;right: 28px;color: #3e90ff;font-size: 23px;"><i class="el-icon-question" /></a>
              </template>
              <el-input
                v-model="template.object"
                type="textarea"
                autosize
                @change="parseTemplate()"
              />
            </el-collapse-item>
            <el-collapse-item name="result">
              <template slot="title">
                Result <a href="https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Data.md#result" target="_blank" style="cursor: help;position: absolute;right: 28px;color: #3e90ff;font-size: 23px;"><i class="el-icon-question" /></a>
              </template>
              <el-input
                v-model="template.result"
                type="textarea"
                autosize
                @change="parseTemplate()"
              />
            </el-collapse-item>
            <el-collapse-item name="context">
              <template slot="title">
                Context <a href="https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Data.md#context" target="_blank" style="cursor: help;position: absolute;right: 28px;color: #3e90ff;font-size: 23px;"><i class="el-icon-question" /></a>
              </template>
              <el-input
                v-model="template.context"
                type="textarea"
                autosize
                @change="parseTemplate()"
              />
            </el-collapse-item>
            <el-collapse-item name="timestamp">
              <template slot="title">
                Timestamp <a href="https://github.com/adlnet/xAPI-Spec/blob/master/xAPI-Data.md#timestamp" target="_blank" style="cursor: help;position: absolute;right: 28px;color: #3e90ff;font-size: 23px;"><i class="el-icon-question" /></a>
              </template>
              <el-input
                v-model="template.timestamp"
                type="textarea"
                autosize
                @change="parseTemplate()"
              />
            </el-collapse-item>
          </el-collapse>
          <el-button type="success" style="float:right;" @click="saveTemplate()">Save Template</el-button>
        </el-card>
      </el-col>
    </el-row>
    <el-row v-if="active_step === 1" type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="12">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;">
            <span>Mapped Statement Preview</span>
          </div>
          <el-input
            type="textarea"
            autosize
            disabled
            :value="JSON.stringify(previewStatement, null, 2)"
          />
        </el-card>
      </el-col>
    </el-row>
    <el-row v-if="active_step === 2" type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="12">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;">
            <el-button plain style="float: right;" icon="el-icon-arrow-left" @click="prevStep">Previous step</el-button>
            <span><strong>Step 3</strong> - Set Source Name</span>
            <el-button type="success" style="float: right;" @click="nextStep">Send Statements</el-button>
          </div>
          <div>
            <div>
              <el-input v-model="data_source_name" style="max-width:200px;" placeholder="Please input name" />
            </div>
          </div>
        </el-card>
      </el-col>
    </el-row>
    <el-row v-if="active_step === 3" type="flex" justify="center" :gutter="10">
      <el-col :xs="24" :sm="24" :md="24" :lg="16" :xl="12">
        <el-card class="box-card" style="margin:20px">
          <div slot="header" style="display: flex;justify-content: space-between;align-items: center;">
            <span><strong>{{ sending_statements_info }}</strong></span>
            <el-button v-if="percentage === 100" type="primary" plain @click="nextStep">Send More?</el-button>
          </div>
          <div>
            <div>
              <el-progress :percentage="percentage" />
            </div>
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Import',
  data() {
    return {
      sending_statements_info: 'Processing Statements...',
      percentage: 0,
      templates: [],
      active_template: null,
      template_options: [],
      template: { actor: '{}', verb: '{}', object: '{}', result: '{}', context: '{}', timestamp: '"' + new Date().toISOString() + '"' },
      data_source_name: '',
      active_step: 0,
      parse_header: [],
      parse_csv: [],
      pasted_csv: '',
      previewStatement: {},
      activeStatementOptions: [],
    };
  },
  created() {
    this.getTemplates();
    this.parseTemplate();
  },
  methods: {
    timer: function(tempo) {
      const vm = this;
      const setIntervalRef = setInterval(function() {
        if (vm.percentage > 99) {
          clearInterval(setIntervalRef);
          return;
        }
        if (vm.percentage === 99) {
          vm.percentage = 1;
          return;
        }
        vm.percentage++;
      }, tempo);
    },
    copiedToClipboard() {
      this.$message({
        message: 'Copied to clipboard.',
        type: 'success',
      });
    },
    loadTemplate(val) {
      this.active_template = val;
      this.template = this.templates.find(t => {
        return t.name === val;
      });
      this.parseTemplate();
    },
    async getTemplates(value) {
      await axios.get('/api/learners/template', {
      }).then(response => {
        this.templates = response.data.data;
        this.template_options = response.data.data.map(template => template.name);
      });
    },
    saveTemplate() {
      if (!this.parseTemplate()) {
        return;
      }

      this.$prompt('Please input template Name', 'Save Template', {
        confirmButtonText: 'Save',
        cancelButtonText: 'Cancel',
      }).then(({ value }) => {
        const template = {
          'name': value,
          'actor': this.template.actor,
          'verb': this.template.verb,
          'object': this.template.object,
          'result': this.template.result,
          'context': this.template.context,
          'timestamp': this.template.timestamp,
        };

        // Send Template To Backend
        axios.post('/api/learners/template', template).then(response => {
          this.getTemplates();
          this.$notify({
            title: 'Success',
            message: response.data.response,
            type: 'success',
            duration: 2000,
          });
        }).catch(error => {
          this.$notify({
            title: 'Error',
            message: error.response.data.error,
            type: 'error',
            duration: 2000,
          });
        });
      });
    },
    sendStatements() {
      // Generate All Statements Data
      this.timer(200);
      const data = [];
      this.parse_csv.forEach(row => {
        const obj = {};

        if (this.template.actor !== '') {
          obj.actor = this.wildcardReplace(this.template.actor, row);
        }
        if (this.template.verb !== '') {
          obj.verb = this.wildcardReplace(this.template.verb, row);
        }
        if (this.template.object !== '') {
          obj.object = this.wildcardReplace(this.template.object, row);
        }
        if (this.template.result !== '') {
          obj.result = this.wildcardReplace(this.template.result, row);
        }
        if (this.template.context !== '') {
          obj.context = this.wildcardReplace(this.template.context, row);
        }
        if (this.template.timestamp !== '') {
          obj.timestamp = this.wildcardReplace(this.template.timestamp, row);
        }
        obj.authority = {
          'objectType': 'Agent',
          'account': {
            'name': this.data_source_name,
            'homePage': 'http://' + this.data_source_name.replace(/\s+/g, '') + '.jeffersoneducation.org',
          },
        };

        data.push(obj);
      });
      console.log(data);

      this.sending_statements_info = 'Sending Statements...';

      // Send Statements To Backend
      const form = new FormData();
      form.append('file', new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' }));
      axios.post('/api/learners/import_to_lrs', form, { headers: { 'Content-Type': 'multipart/form-data' }}).then(response => {
        this.sending_statements_info = response.data.response;
        this.percentage = 100;
        this.$notify({
          title: 'Success',
          message: response.data.response,
          type: 'success',
          duration: 2000,
        });
      }).catch(error => {
        this.sending_statements_info = error.response.data.response;
        this.percentage = 100;
        this.$notify({
          title: 'Error',
          message: error.response.data.response,
          type: 'error',
          duration: 2000,
        });
      });
    },
    nextStep() {
      if (this.active_step === 0) {
        if (this.parse_csv.length < 1 && this.csvJSON(this.pasted_csv).length < 1) {
          this.$notify.error({
            title: 'Error',
            message: 'Please Import Data',
            duration: 2000,
          });
          return;
        }
      }
      if (this.active_step === 1 && !this.parseTemplate()) {
        return;
      }
      if (this.active_step === 2) {
        if (this.data_source_name === '') {
          this.$notify.error({
            title: 'Error',
            message: 'Please Name the Data Source',
            duration: 2000,
          });
          return;
        }
      }
      this.active_step++;

      if (this.active_step === 3) {
        this.sendStatements();
      }

      if (this.active_step === 4) {
        this.reset();
      }
    },
    reset() {
      this.sending_statements_info = 'Processing Statements...';
      this.percentage = 0;
      this.active_template = null;
      this.template = { actor: '{}', verb: '{}', object: '{}', result: '{}', context: '{}', timestamp: '"' + new Date().toISOString() + '"' };
      this.data_source_name = '';
      this.active_step = 0;
      this.parse_header = [];
      this.parse_csv = [];
      this.pasted_csv = '';
      this.previewStatement = {};
      this.activeStatementOptions = [];
      this.parseTemplate();
    },
    prevStep() {
      if (this.active_step-- < 0) {
        this.active_step = 3;
      }

      if (this.active_step <= 0) {
        this.reset();
      }
    },
    wildcardReplace(text, row) {
      this.parse_header.forEach(wildcard => {
        text = text.replaceAll('{{' + wildcard + '}}', row[wildcard].trim());
      });
      if (!text) {
        return undefined;
      }
      return JSON.parse(text);
    },
    parseTemplate() {
      try {
        this.previewStatement = {};
        if (this.template.actor) {
          this.previewStatement.actor = this.wildcardReplace(this.template.actor, this.parse_csv[1]);
        }
        if (this.template.verb) {
          this.previewStatement.verb = this.wildcardReplace(this.template.verb, this.parse_csv[1]);
        }
        if (this.template.object) {
          this.previewStatement.object = this.wildcardReplace(this.template.object, this.parse_csv[1]);
        }
        if (this.template.result) {
          this.previewStatement.result = this.wildcardReplace(this.template.result, this.parse_csv[1]);
        }
        if (this.template.context) {
          this.previewStatement.context = this.wildcardReplace(this.template.context, this.parse_csv[1]);
        }
        if (this.template.timestamp) {
          this.previewStatement.timestamp = this.wildcardReplace(this.template.timestamp, this.parse_csv[1]);
        }

        return true;
      } catch (error) {
        this.previewStatement = 'Invalid JSON...';
        this.$notify.error({
          title: 'Error',
          message: 'Unable to map template, Invalid JSON',
          duration: 2000,
        });
        return false;
      }
    },
    csvJSON(csv){
      var vm = this;
      var lines = csv.split('\n');
      var result = [];
      var headers = lines[0].split(',').map(function(item) {
        return item.trim();
      });
      vm.parse_header = headers;

      lines.map(function(line, indexLine){
        if (indexLine < 1) {
          return;
        } // Jump header line

        var obj = {};
        var currentline = vm.csvToArray(line)[0];

        headers.map(function(header, indexHeader){
          obj[header] = currentline[indexHeader];
        });

        result.push(obj);
      });

      result.pop(); // remove the last item because undefined values
      return result; // JavaScript object
    },
    loadCSV(e) {
      var vm = this;
      if (window.FileReader) {
        var reader = new FileReader();
        reader.readAsText(e.target.files[0]);
        // Handle errors load
        reader.onload = function(event) {
          var csv = event.target.result;
          vm.parse_csv = vm.csvJSON(csv);
        };
        reader.onerror = function(evt) {
          if (evt.target.error.name === 'NotReadableError') {
            alert("Canno't read file !");
          }
        };
      } else {
        alert('FileReader are not supported in this browser.');
      }
    },
    csvToArray(text) {
      let p = '', row = [''], i = 0, r = 0, s = !0, l;
      const ret = [row];
      for (l of text) {
        if (l === '"') {
          if (s && l === p) {
            row[i] += l;
          }
          s = !s;
        } else if (l === ',' && s) {
          l = row[++i] = '';
        } else if (l === '\n' && s) {
          if (p === '\r') {
            row[i] = row[i].slice(0, -1);
          }
          row = ret[++r] = [l = '']; i = 0;
        } else {
          row[i] += l;
        }
        p = l;
      }
      return ret;
    },
  },
};
</script>

<style lang="scss" scoped>
.el-collapse-item {
  position: relative;
}
.separator {
  text-align: center;
  font-weight: 600;
  margin: 30px 0px 10px;
  padding: 0;
  position: relative;
}
.separator::before {
    display: inline-block;
    content: "";
    border-top: 1px solid #e6ebf5;
    width: 46%;
    margin: 0;
    /* -webkit-transform: translateY(-1rem); */
    /* transform: translateY(-1rem); */
    position: absolute;
    left: 0;
    margin-top: 6px;
}
.separator::after {
    display: inline-block;
    content: "";
    border-top: 1px solid #e6ebf5;
    width: 46%;
    margin: 0;
    /* -webkit-transform: translateY(-1rem); */
    /* transform: translateY(-1rem); */
    position: absolute;
    right: 0;
    margin-top: 6px;
}
</style>
