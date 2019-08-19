<template>
    <el-container>
        <el-header>
            <label class="name">{{name}}</label>
        </el-header>
        <el-main>
            <el-button type="primary" icon="el-icon-edit" @click="dialogVisible=true">添加</el-button>
            <el-table
                    :data="cases"
                    border
                    style="width: 100%">
                <el-table-column
                        type="index"
                        label="编号"
                        width="50">
                </el-table-column>
                <el-table-column
                        prop="title"
                        label="标题"
                        align="center"
                        header-align="center">
                </el-table-column>
                <el-table-column
                        fixed="right"
                        label="操作"
                        width="100">
                    <template slot-scope="scope">
                        <el-button type="text" size="small" @click="edit(scope.$index, scope.row)">编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-dialog
                    title="添加案例"
                    :visible.sync="dialogVisible"
                    width="80%"
                    :before-close="handleClose">
                <el-steps :active="active" finish-status="success">
                    <el-step title="步骤 1"></el-step>
                    <el-step title="步骤 2"></el-step>
                    <el-step title="步骤 3"></el-step>
                </el-steps>
                <el-form :model="caseObj" :rules="rule0" ref="form0" label-width="100px" v-show="active===0">
                    <el-form-item label="案例题目" prop="title">
                        <el-input v-model="caseObj.title"></el-input>
                    </el-form-item>
                    <el-form-item label="案例介绍" prop="introduce">
                        <el-input type="textarea" :rows="3" v-model="caseObj.introduce"></el-input>
                    </el-form-item>
                    <el-form-item label="案例图片" prop="img">
                        <el-upload
                                class="avatar-uploader"
                                action="/imgs"
                                :show-file-list="false"
                                :on-success="handleAvatarSuccess"
                                :before-upload="beforeAvatarUpload"
                                name="img"
                                :headers="{'X-XSRF-TOKEN':csrfToken}"
                                accept=".jpg,.png">
                            <img v-if="caseObj.img" :src="caseObj.img" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="案例答案" prop="answer">
                        <el-input v-model="caseObj.answer"></el-input>
                    </el-form-item>
                    <el-form-item label="案例提示" prop="tip">
                        <el-input v-model="caseObj.tip"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button style="margin-top: 12px;" @click="next('form0')">下一步</el-button>
                    </el-form-item>
                </el-form>
                <el-form :model="caseObj" :rules="rule1" ref="form1" label-width="100px" v-show="active===1">
                    <el-form-item label="学科属性" prop="subject">
                        <el-select v-model="caseObj.subject" placeholder="请选择">
                            <el-option
                                    v-for="item in subject"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="思维属性" prop="think">
                        <el-select v-model="caseObj.think" placeholder="请选择">
                            <el-option
                                    v-for="item in think"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="思维难度" prop="think_difficulty">
                        <el-select v-model="caseObj.think_difficulty" placeholder="请选择">
                            <el-option
                                    v-for="item in difficulty"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="能力属性" prop="ability">
                        <el-select v-model="caseObj.ability" placeholder="请选择">
                            <el-option
                                    v-for="item in ability"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="能力难度" prop="ability_difficulty">
                        <el-select v-model="caseObj.ability_difficulty" placeholder="请选择">
                            <el-option
                                    v-for="item in difficulty"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="知识属性" prop="knowledge">
                        <el-select v-model="caseObj.knowledge" placeholder="请选择">
                            <el-option
                                    v-for="item in knowledge"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="知识难度" prop="knowledge_difficulty">
                        <el-select v-model="caseObj.knowledge_difficulty" placeholder="请选择">
                            <el-option
                                    v-for="item in difficulty"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button style="margin-top: 12px;" @click="pre">上一步</el-button>
                        <el-button style="margin-top: 12px;" @click="next('form1')">下一步</el-button>
                    </el-form-item>
                </el-form>
                <el-form :model="caseObj" :rules="rule2" ref="form2" label-width="100px" v-show="active===2">
                    <el-form-item label="地点" prop="place">
                        <el-input v-model="caseObj.place"></el-input>
                    </el-form-item>
                    <el-form-item label="场景" prop="scene">
                        <el-input v-model="caseObj.scene"></el-input>
                    </el-form-item>
                    <el-form-item label="人物" prop="character">
                        <el-input v-model="caseObj.character"></el-input>
                    </el-form-item>
                    <el-form-item label="道具" prop="tool">
                        <el-input v-model="caseObj.tool"></el-input>
                    </el-form-item>
                    <el-form-item label="问题承载" prop="problem">
                        <el-select v-model="caseObj.problem" placeholder="请选择">
                            <el-option
                                    v-for="item in problem"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="收获习得" prop="result">
                        <el-select v-model="caseObj.result" placeholder="请选择">
                            <el-option
                                    v-for="item in result"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button style="margin-top: 12px;" @click="pre">上一步</el-button>
                        <el-button style="margin-top: 12px;" @click="next('form2')">提交</el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
        </el-main>
    </el-container>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "Document",
        computed: {
            ...mapState(['name'])
        },
        data() {
            return {
                cases: [],
                caseObj: {
                    title: '',
                    introduce: '',
                    img: '',
                    answer: '',
                    tip: '',
                    subject: '',
                    think: '',
                    think_difficulty: '',
                    ability: '',
                    ability_difficulty: '',
                    knowledge: '',
                    knowledge_difficulty: '',
                    place: '',
                    scene: '',
                    character: '',
                    tool: '',
                    problem: '',
                    result: '',
                },
                dialogVisible: false,
                active: 0,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule0: {
                    title: [{required: true, message: '请输入案例题目', trigger: 'blur'}],
                    introduce: [{required: true, message: '请输入案例介绍', trigger: 'blur'}],
                    img: [{required: true, message: '请上传案例图片', trigger: 'blur'}],
                    answer: [{required: true, message: '请输入答案', trigger: 'blur'}],
                    tip: [{required: true, message: '请输入提示', trigger: 'blur'}],
                },
                rule1: {
                    subject: [{required: true, message: '请选择', trigger: 'blur'}],
                    think: [{required: true, message: '请选择', trigger: 'blur'}],
                    think_difficulty: [{required: true, message: '请选择', trigger: 'blur'}],
                    ability: [{required: true, message: '请选择', trigger: 'blur'}],
                    ability_difficulty: [{required: true, message: '请选择', trigger: 'blur'}],
                    knowledge: [{required: true, message: '请选择', trigger: 'blur'}],
                    knowledge_difficulty: [{required: true, message: '请选择', trigger: 'blur'}],
                },
                rule2: {
                    place: [{required: true, message: '请输入', trigger: 'blur'}],
                    scene: [{required: true, message: '请输入', trigger: 'blur'}],
                    character: [{required: true, message: '请输入', trigger: 'blur'}],
                    tool: [{required: true, message: '请输入', trigger: 'blur'}],
                    problem: [{required: true, message: '请选择', trigger: 'blur'}],
                    result: [{required: true, message: '请选择', trigger: 'blur'}],
                },
                subject: [
                    {value: 0, label: "数学"},
                    {value: 1, label: "编程"},
                    {value: 2, label: "AI"},
                ],
                think: [
                    {value: 0, label: "逻辑思维"},
                    {value: 1, label: "抽象思维"},
                    {value: 2, label: "计算思维"},
                    {value: 3, label: "分布思维"},
                ],
                ability: [
                    {value: 0, label: "观察力"},
                    {value: 1, label: "分析力"},
                    {value: 2, label: "实践力"},
                ],
                knowledge: [
                    {value: 0, label: "数学推理"},
                    {value: 1, label: "空间想象"},
                ],
                difficulty: [
                    {value: 0, label: "容易"},
                    {value: 1, label: "一般"},
                    {value: 2, label: "困难"},
                ],
                problem: [
                    {value: 0, label: "拼图"},
                    {value: 1, label: "流程图"},
                    {value: 2, label: "运算"},
                    {value: 3, label: "代码逻辑"},
                    {value: 4, label: "观察"},
                    {value: 5, label: "编程环境"},
                ],
                result: [
                    {value: 0, label: "单一问题训练"},
                    {value: 1, label: "综合问题解决"},
                    {value: 2, label: "工具使用技巧"},
                ],
            }
        },
        methods: {
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.caseObj = {};
                        done();
                    })
                    .catch(_ => {
                    });
            },
            next(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if (this.active === 2) {
                            axios.post('/documents', this.caseObj)
                                .then((response) => {
                                    this.dialogVisible = false;
                                    this.active = 0;
                                    this.$refs.form0.resetFields();
                                    this.$refs.form1.resetFields();
                                    this.$refs.form2.resetFields();
                                    this.getCases();
                                })
                                .catch((error) => {
                                    console.log(error);
                                    this.$message.error('添加失败，请稍后重试');
                                })
                        } else {
                            this.active++
                        }
                    } else {
                        return false;
                    }
                })
            },
            pre() {
                this.active--;
            },
            handleAvatarSuccess(res, file) {
                this.caseObj.img = res;
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },
            getCases() {
                axios.get('/documents')
                    .then((response) => {
                        this.cases = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            edit(index, row) {
                axios.get('/documents/' + row.id)
                    .then((response) => {
                        this.caseObj = response.data;
                        this.dialogVisible = true;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
        },
        mounted() {
            this.getCases();
        }
    }
</script>

<style scoped>
    .name {
        float: right;
    }

    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>