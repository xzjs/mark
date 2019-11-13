<template>
    <div>
        <el-button type="primary" icon="el-icon-edit" @click="showDialog">添加</el-button>
        <el-table
                border
                :data="computers"
                style="width: 100%">
            <el-table-column prop="id" label="编号" width="50px">
            </el-table-column>
            <el-table-column prop="url" label="网址">
            </el-table-column>
            <el-table-column prop="images" label="图片">
                <template slot-scope="scope">
                    <div v-for="image in scope.row.images">
                        <el-image :src="image.url+'-small'" fit="contain"
                                  :preview-src-list="getImgList(scope.row.images)"></el-image>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="point" label="知识点">
                <template slot-scope="scope">{{scope.row.point.join('-')}}</template>
            </el-table-column>
            <el-table-column prop="target" label="功能/目标">
            </el-table-column>
            <el-table-column prop="contents" label="背景/内容">
            </el-table-column>
            <el-table-column label="pisa">
                <el-table-column
                        prop="communication"
                        label="沟通" width="50px">
                </el-table-column>
                <el-table-column
                        prop="strategy"
                        label="策略" width="50px">
                </el-table-column>
                <el-table-column
                        prop="mathematicization"
                        label="数字化" width="50px">
                </el-table-column>
                <el-table-column
                        prop="symbol"
                        label="符号" width="50px">
                </el-table-column>
                <el-table-column
                        prop="representation"
                        label="表征" width="50px">
                </el-table-column>
                <el-table-column
                        prop="reasoning"
                        label="推理论证" width="50px">
                </el-table-column>
            </el-table-column>

            <el-table-column label="计算思维">
                <el-table-column
                        prop="abstract"
                        label="抽象" width="50px">
                </el-table-column>
                <el-table-column
                        prop="summarize"
                        label="概括" width="50px">
                </el-table-column>
                <el-table-column
                        prop="disassemble"
                        label="分解" width="50px">
                </el-table-column>
                <el-table-column
                        prop="assessment"
                        label="评估" width="50px">
                </el-table-column>
            </el-table-column>

            <el-table-column label="算法思维">
                <el-table-column
                        prop="distinguish"
                        label="辨明" width="50px">
                </el-table-column>
                <el-table-column
                        prop="understand"
                        label="理解" width="50px">
                </el-table-column>
                <el-table-column
                        prop="improvement"
                        label="改进" width="50px">
                </el-table-column>
                <el-table-column
                        prop="transformation"
                        label="转换" width="50px">
                </el-table-column>
            </el-table-column>

            <el-table-column
                    prop="programThink"
                    label="编程思维">
                <template slot-scope="scope">{{scope.row.programThink.join('-')}}</template>
            </el-table-column>
            <el-table-column v-if="user.type === 0" label="标注人" width="100px" prop="user.name">
            </el-table-column>
            <el-table-column v-if="user.type === 0" label="花费时间(s)" prop="cost" width="50px">
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="操作"
                    width="100">
                <template slot-scope="scope">
                    <el-button @click="edit(scope.$index, scope.row)" size="small">编辑</el-button>
                    <br>
                    <el-button @click="del(scope.$index, scope.row)" type="danger" size="small">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="pagination">
            <el-pagination background layout="prev, pager, next" :total="total"
                           :page-size="10"
                           @current-change="currentChange"
                           @prev-click="currentChange"
                           @next-click="nextClick"
            ></el-pagination>
        </div>
        <el-dialog
                title="标注"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="computer" :rules="rule" ref="form" label-width="200px">
                <el-form-item label="网址">
                    <el-input placeholder="请输入网址" v-model="computer.url">
                        <template slot="prepend">Http://</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="图片">
                    <el-upload
                            ref="images"
                            list-type="picture-card"
                            action="/upload"
                            name="img"
                            accept="image/jpeg,image/png"
                            :limit=10
                            :file-list="computer.images"
                            :on-success="uploadSuccess"
                            :on-remove="deleteSuccess"
                            :headers="{'X-XSRF-TOKEN':csrfToken}">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="知识点" prop="point">
                    <el-cascader v-model="computer.point" :options="pointOptions"></el-cascader>
                </el-form-item>
                <el-form-item label="功能/目标" prop="target">
                    <el-input
                            type="textarea"
                            :rows="2"
                            v-model="computer.target">
                    </el-input>
                </el-form-item>
                <el-form-item label="背景/内容" prop="contents">
                    <el-input
                            type="textarea"
                            :rows="2"
                            v-model="computer.contents">
                    </el-input>
                </el-form-item>

                <el-form-item label="PISA能力等级-沟通" prop="communication">
                    <el-select v-model="computer.communication" placeholder="沟通">
                        <el-option v-for="item in pisa['communication']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-策略" prop="strategy">
                    <el-select v-model="computer.strategy" placeholder="策略">
                        <el-option v-for="item in pisa['strategy']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-数学化" prop="mathematicization">
                    <el-select v-model="computer.mathematicization" placeholder="数学化">
                        <el-option v-for="item in pisa['mathematicization']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-符号" prop="symbol">
                    <el-select v-model="computer.symbol" placeholder="符号">
                        <el-option v-for="item in pisa['symbol']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-表征" prop="representation">
                    <el-select v-model="computer.representation" placeholder="表征">
                        <el-option v-for="item in pisa['representation']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-推理论证" prop="reasoning">
                    <el-select v-model="computer.reasoning" placeholder="推理论证">
                        <el-option v-for="item in pisa['reasoning']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="计算思维-抽象" prop="abstract">
                    <el-select v-model="computer.abstract" placeholder="抽象">
                        <el-option v-for="item in thinkOptions['abstract']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="计算思维-概括" prop="summarize">
                    <el-select v-model="computer.summarize" placeholder="概括">
                        <el-option v-for="item in thinkOptions['summarize']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="计算思维-分解" prop="disassemble">
                    <el-select v-model="computer.disassemble" placeholder="分解">
                        <el-option v-for="item in thinkOptions['disassemble']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="计算思维-评估" prop="assessment">
                    <el-select v-model="computer.assessment" placeholder="评估">
                        <el-option v-for="item in thinkOptions['assessment']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="算法思维-辨明" prop="distinguish">
                    <el-select v-model="computer.distinguish" placeholder="辨明">
                        <el-option v-for="item in thinkOptions['distinguish']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="算法思维-理解" prop="understand">
                    <el-select v-model="computer.understand" placeholder="理解">
                        <el-option v-for="item in thinkOptions['understand']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="算法思维-改进" prop="improvement">
                    <el-select v-model="computer.improvement" placeholder="改进">
                        <el-option v-for="item in thinkOptions['improvement']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="算法思维-转换" prop="transformation">
                    <el-select v-model="computer.transformation" placeholder="转换">
                        <el-option v-for="item in thinkOptions['transformation']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="编程思维" prop="programThink">
                    <el-cascader v-model="computer.programThink" :options="programThinkOptions"></el-cascader>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="create">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Computer",
        data() {
            return {
                total: 0,
                computer: {
                    url: '',
                    images: [],
                    point: '',
                    target: '',
                    contents: '',
                    //pisa
                    communication: '',
                    strategy: '',
                    mathematicization: '',
                    symbol: '',
                    representation: '',
                    reasoning: '',
                    //计算思维
                    abstract: '',
                    summarize: '',
                    disassemble: '',
                    assessment: '',
                    //算法思维
                    distinguish: '',
                    understand: '',
                    improvement: '',
                    transformation: '',
                    //编程思维
                    programThink: '',
                    start: 0,
                },
                computers: [],
                pisa: [],
                pointOptions: [],
                programThinkOptions: [],
                thinkOptions: [],
                dialogVisible: false,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule: {
                    point: [{required: true, message: '请选择', trigger: 'blur'}],
                    target: [{require: true, message: '请填写', trigger: 'blur'}],
                    contents: [{require: true, message: '请填写', trigger: 'blur'}],

                    communication: [{required: true, message: '请选择', trigger: 'blur'}],
                    strategy: [{required: true, message: '请选择', trigger: 'blur'}],
                    mathematicization: [{required: true, message: '请选择', trigger: 'blur'}],
                    symbol: [{required: true, message: '请选择', trigger: 'blur'}],
                    representation: [{required: true, message: '请选择', trigger: 'blur'}],
                    reasoning: [{required: true, message: '请选择', trigger: 'blur'}],
                    knowledge: [{required: true, message: '请选择', trigger: 'blur'}],

                    abstract: [{required: true, message: '请选择', trigger: 'blur'}],
                    summarize: [{required: true, message: '请选择', trigger: 'blur'}],
                    disassemble: [{required: true, message: '请选择', trigger: 'blur'}],
                    assessment: [{required: true, message: '请选择', trigger: 'blur'}],

                    distinguish: [{required: true, message: '请选择', trigger: 'blur'}],
                    understand: [{required: true, message: '请选择', trigger: 'blur'}],
                    improvement: [{required: true, message: '请选择', trigger: 'blur'}],
                    transformation: [{required: true, message: '请选择', trigger: 'blur'}],

                    programThink: [{required: true, message: '请选择', trigger: 'blur'}],
                }
            }
        },
        computed: {
            ...mapState(['user']),
        },
        methods: {
            showDialog() {
                this.dialogVisible = true;
                this.computer.start = (new Date()).getTime();
            },
            getComputers: function (params) {
                axios.get('/computers', {params: params})
                    .then(response => {
                        this.computers = response.data.data;
                        this.total = response.data.total;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.computer = {};
                        this.$refs.images.clearFiles();
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        if (this.computer.images || this.computer.url) {
                            if (this.computer.id) {
                                axios.put('/computers/' + this.computer.id, this.computer)
                                    .then(response => {
                                        this.refresh();
                                    })
                                    .catch(error => {
                                        this.$message.error(error.response.data);
                                    })
                            } else {
                                axios.post('/computers', this.computer)
                                    .then((response) => {
                                        this.refresh();
                                    })
                                    .catch((error) => {
                                        this.$message.error(error.response.data);
                                    })
                            }
                        } else {
                            this.$message.error('url和图片不可都为空');
                        }
                    } else {
                        return false;
                    }
                });
            },
            refresh() {
                this.getComputers();
                this.computer = {};
                this.$refs.form.resetFields();
                this.dialogVisible = false;
            },
            uploadSuccess(response, file, fileList) {
                this.computer.images = fileList;
            },
            deleteSuccess(file, fileList) {
                this.computer.images = fileList;
            },
            del(index, row) {
                axios.delete('/mathmarks/' + row.id)
                    .then(response => {
                        this.getMaths();
                    })
                    .catch(error => {
                        this.$message.error('没有权限');
                    })
            },
            edit(index, row) {
                if (row.user_id == this.user.id || this.user.roles.indexOf('管理员') !== -1) {
                    this.computer = row;
                    this.showDialog();
                } else {
                    this.$message.error('只能编辑自己的标注');
                }
            },
            getImgList(imgs) {
                let list = [];
                for (let i = 0; i < imgs.length; i++) {
                    list.push(imgs[i].url);
                }
                return list;
            },
            currentChange(page) {
                this.getMaths({'page': page});
            },
            preCLick(page) {
                this.getMaths({'page': page - 1});
            },
            nextClick(page) {
                this.getMaths({'page': page + 1});
            }
        },
        mounted() {
            this.getComputers();
            axios.get('/json/pisa.json')
                .then(response => {
                    this.pisa = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('/json/computer_point.json')
                .then(response => {
                    this.pointOptions = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('/json/program_think.json')
                .then(response => {
                    this.programThinkOptions = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('/json/think.json')
                .then(response => {
                    this.thinkOptions = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
</script>

<style scoped>
    .pagination {
        text-align: center;
        margin: 20px;
    }
</style>