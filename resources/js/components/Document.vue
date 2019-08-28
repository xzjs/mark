<template>
    <div>
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
                width="50%"
                :before-close="handleClose">
            <el-steps :active="active" finish-status="success">
                <el-step title="步骤 1"></el-step>
                <el-step title="步骤 2"></el-step>
                <el-step title="步骤 3"></el-step>
            </el-steps>
            <el-form :model="caseObj" :rules="rule0" ref="form0" label-width="100px" v-show="active===0">
                <el-form-item label="书名" prop="bookId">
                    <el-select v-model="caseObj.bookId" placeholder="请选择">
                        <el-option
                                v-for="book in books"
                                :key="book.id"
                                :label="book.name"
                                :value="book.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="页码" prop="pageNumber">
                    <el-input v-model="caseObj.pageNumber" placeholder="请输入页码" type="number"></el-input>
                </el-form-item>
                <el-form-item label="案例图片" prop="img">
                    <el-upload
                            class="avatar-uploader"
                            action="/upload"
                            :show-file-list="false"
                            :on-success="uploadOcr"
                            name="img"
                            :data="upload"
                            :headers="{'X-XSRF-TOKEN':csrfToken}"
                            accept=".jpg,.png,.bmp">
                        <img v-if="caseObj.img" :src="caseObj.img" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="案例介绍" prop="introduce">
                    <el-input type="textarea" :rows="3" v-model="caseObj.introduce"></el-input>
                </el-form-item>
                <el-form-item label="补充图片">
                    <el-upload action="/upload"
                               list-type="picture-card"
                               :on-success="uploadSupplementImg"
                               :on-remove="removeSupplementImg"
                               name="img"
                               :headers="{'X-XSRF-TOKEN':csrfToken}"
                               accept=".jpg,.png,.bmp">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="案例答案" prop="answer">
                    <el-upload
                            class="avatar-uploader"
                            action="/upload"
                            :show-file-list="false"
                            :on-success="uploadAnswer"
                            name="img"
                            :headers="{'X-XSRF-TOKEN':csrfToken}"
                            accept=".jpg,.png,.bmp">
                        <img v-if="caseObj.answer" :src="caseObj.answer" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="案例提示" prop="tip">
                    <el-upload
                            class="avatar-uploader"
                            action="/upload"
                            :show-file-list="false"
                            :on-success="uploadTip"
                            name="img"
                            :headers="{'X-XSRF-TOKEN':csrfToken}"
                            accept=".jpg,.png,.bmp">
                        <img v-if="caseObj.tip" :src="caseObj.tip" class="avatar">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item>
                    <el-button style="margin-top: 12px;" @click="next('form0')">下一步</el-button>
                </el-form-item>
            </el-form>
            <el-form :model="caseObj" :rules="rule1" ref="form1" label-width="100px" v-show="active===1">
                <el-form-item label="学科属性" prop="subject">
                    <el-select v-model="caseObj.subject" multiple placeholder="请选择">
                        <el-option
                                v-for="item in subject"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item v-for="(t,index) in caseObj.think" label="思维">
                    <el-select v-model="t.type" placeholder="属性">
                        <el-option
                                v-for="item in think"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-select v-model="t.difficulty" placeholder="难度">
                        <el-option
                                v-for="item in difficulty"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-button v-if="caseObj.think.length>1" type="danger" icon="el-icon-delete" circle
                               @click="deleteThink(index)"></el-button>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-plus" @click="addThink"></el-button>
                </el-form-item>
                <el-form-item v-for="(t,index) in caseObj.ability"  label="能力">
                    <el-select v-model="t.type" placeholder="属性">
                        <el-option
                                v-for="item in ability"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-select v-model="t.difficulty" placeholder="难度">
                        <el-option
                                v-for="item in difficulty"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-button v-if="caseObj.ability.length>1" type="danger" icon="el-icon-delete" circle
                               @click="deleteAbility(index)"></el-button>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-plus" @click="addAbility"></el-button>
                </el-form-item>
                <el-form-item v-for="(t,index) in caseObj.knowledge" label="知识">
                    <el-select v-model="t.type" placeholder="属性">
                        <el-option
                                v-for="item in knowledge"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-select v-model="t.difficulty" placeholder="难度">
                        <el-option
                                v-for="item in difficulty"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-button v-if="caseObj.knowledge.length>1" type="danger" icon="el-icon-delete" circle
                               @click="deleteKnowledge(index)"></el-button>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-plus" @click="addKnowledge"></el-button>
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
    </div>
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
                    bookId: '',
                    pageNumber: 0,
                    introduce: '',
                    img: '',
                    supplementImages: [],
                    answer: '',
                    tip: '',
                    subject: [],
                    think: [{
                        type: '',
                        difficulty: '',
                    }],
                    ability: [{
                        type: '',
                        difficulty: '',
                    }],
                    knowledge: [{
                        type: '',
                        difficulty: '',
                    }],
                    place: '',
                    scene: '',
                    character: '',
                    tool: '',
                    problem: '',
                    result: '',
                },
                books: [],
                dialogVisible: false,
                active: 1,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule0: {
                    bookId: [{required: true, message: '请选择书名', trigger: 'blur'}],
                    pageNumber: [{required: true, message: '请输入页码', trigger: 'blur'}],
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
                upload: {
                    type: "ocr",
                }
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
            uploadOcr(res, file) {
                this.caseObj.img = res.path;
                this.caseObj.introduce = res.ocr;
            },
            uploadSupplementImg(res, file) {
                this.caseObj.supplementImages.push(res.path);
            },
            removeSupplementImg(file, fileList) {
                let images = [];
                for (let i = 0; i < fileList.length; i++) {
                    images.push(fileList[i].response.path);
                }
                this.caseObj.supplementImages = images;
            },
            uploadAnswer(res, file) {
                this.caseObj.answer = res.path;
            },
            uploadTip(res, file) {
                this.caseObj.tip = res.path;
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
            getBooks() {
                axios.get('/books')
                    .then((response) => {
                        this.books = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            addThink() {
                this.caseObj.think.push({type: '', difficulty: ''});
            },
            deleteThink(index) {
                this.caseObj.think.splice(index, 1);
            },
            addAbility() {
                this.caseObj.ability.push({type: '', difficulty: ''});
            },
            deleteAbility(index) {
                this.caseObj.ability.splice(index, 1);
            },
            addKnowledge() {
                this.caseObj.knowledge.push({type: '', difficulty: ''});
            },
            deleteKnowledge(index) {
                this.caseObj.knowledge.splice(index, 1);
            },
        },
        mounted() {
            this.getCases();
            this.getBooks();
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