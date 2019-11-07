<template>
    <div>
        <el-button type="primary" icon="el-icon-edit" @click="showDialog">添加</el-button>
        <el-table
                border
                :data="maths"
                style="width: 100%">
            <el-table-column
                    prop="id"
                    label="编号"
                    width="50px">
            </el-table-column>
            <el-table-column
                    prop="book_id"
                    label="案例编号"
                    width="50px">
            </el-table-column>
            <el-table-column
                    prop="scene"
                    label="情境">
                <template slot-scope="scope">
                    <el-tag>{{scope.row.scene.join('-')}}</el-tag>
                </template>
            </el-table-column>
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
            <el-table-column
                    prop="knowledge"
                    label="知识情景/场景">
            </el-table-column>
            <el-table-column
                    prop="point"
                    label="知识点">
                <template slot-scope="scope">
                    <el-tag v-for="item in scope.row.point">{{item.join('-')}}</el-tag>
                </template>
            </el-table-column>
            <el-table-column v-if="user.type === 0" label="标注人" prop="" width="50px">
            </el-table-column>
            <el-table-column v-if="user.type === 0" label="花费时间(s)" prop="cost" width="50px">
            </el-table-column>
        </el-table>
        <el-dialog
                title="标注"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="math" :rules="rule" ref="form" label-width="200px">
                <el-form-item label="案例编号" prop="book_id">
                    <el-select v-model="math.book_id" placeholder="请选择">
                        <el-option
                                v-for="id in ids"
                                :key="id"
                                :label="id"
                                :value="id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="情境" prop="scene">
                    <el-cascader v-model="math.scene" :options="sceneOptions"></el-cascader>
                </el-form-item>
                <el-form-item label="PISA能力等级-沟通" prop="communication">
                    <el-select v-model="math.communication" placeholder="沟通">
                        <el-option v-for="item in pisa['communication']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-策略" prop="strategy">
                    <el-select v-model="math.strategy" placeholder="策略">
                        <el-option v-for="item in pisa['strategy']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-数学化" prop="mathematicization">
                    <el-select v-model="math.mathematicization" placeholder="数学化">
                        <el-option v-for="item in pisa['mathematicization']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-符号" prop="symbol">
                    <el-select v-model="math.symbol" placeholder="符号">
                        <el-option v-for="item in pisa['symbol']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-表征" prop="representation">
                    <el-select v-model="math.representation" placeholder="表征">
                        <el-option v-for="item in pisa['representation']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="PISA能力等级-推理论证" prop="reasoning">
                    <el-select v-model="math.reasoning" placeholder="推理论证">
                        <el-option v-for="item in pisa['reasoning']"
                                   :label="item['label']"
                                   :value="item['value']">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="知识情景/场景" prop="knowledge">
                    <el-select v-model="math.knowledge" placeholder="请选择">
                        <el-option
                                v-for="knowledge in knowledges"
                                :key="knowledge"
                                :label="knowledge"
                                :value="knowledge">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="知识点" prop="point">
                    <el-cascader v-model="math.point" :options="pointOptions" :props={multiple:true}></el-cascader>
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
        name: "Math",
        data() {
            return {
                ids: [],
                math: {
                    book_id: '',
                    scene: '',
                    communication: '',
                    strategy: '',
                    mathematicization: '',
                    symbol: '',
                    representation: '',
                    reasoning: '',
                    knowledge: '',
                    point: '',
                    start: '',
                },
                maths: [],
                sceneOptions: [],
                pisa: [],
                knowledges: [],
                pointOptions: [],
                dialogVisible: false,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule: {
                    book_id: [{required: true, message: '请选择', trigger: 'blur'}],
                    scene: [{required: true, message: '请选择', trigger: 'blur'}],
                    communication: [{required: true, message: '请选择', trigger: 'blur'}],
                    strategy: [{required: true, message: '请选择', trigger: 'blur'}],
                    mathematicization: [{required: true, message: '请选择', trigger: 'blur'}],
                    symbol: [{required: true, message: '请选择', trigger: 'blur'}],
                    representation: [{required: true, message: '请选择', trigger: 'blur'}],
                    reasoning: [{required: true, message: '请选择', trigger: 'blur'}],
                    knowledge: [{required: true, message: '请选择', trigger: 'blur'}],
                    point: [{required: true, message: '请选择', trigger: 'blur'}],
                }
            }
        },
        computed: {
            ...mapState(['user'])
        },
        methods: {
            showDialog() {
                this.dialogVisible = true;
                this.math.start = (new Date()).getTime();
            },
            getMaths: function () {
                axios.get('mathmarks')
                    .then(response => {
                        this.maths = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            getIds: function () {
                axios.get('/books')
                    .then((response) => {
                        for (let i = 0; i < response.data.length; i++) {
                            this.ids.push(response.data[i].id);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.math = {};
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        axios.post('/mathmarks', this.math)
                            .then((response) => {
                                this.getMaths();
                                this.$refs.form.resetFields();
                                this.dialogVisible = false;
                            })
                            .catch((error) => {
                                console.log(error.response);
                                this.$message.error(error.response.data);
                            })
                    } else {
                        return false;
                    }
                });
            }
        },
        mounted() {
            this.getMaths();

            this.getIds();

            axios.get('/json/scene.json')
                .then(response => {
                    this.sceneOptions = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('/json/pisa.json')
                .then(response => {
                    this.pisa = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('/json/knowledge.json')
                .then(response => {
                    this.knowledges = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            axios.get('/json/point.json')
                .then(response => {
                    this.pointOptions = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        }
    }
</script>

<style scoped>

</style>