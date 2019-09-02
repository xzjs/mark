<template>
    <div>
        <el-button type="primary" icon="el-icon-edit" @click="showDialog">添加</el-button>
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
                    prop="book.name"
                    label="书名">
            </el-table-column>
            <el-table-column
                    prop="pageNumber"
                    label="页码"
                    width="50">
            </el-table-column>
            <el-table-column
                    label="图片">
                <template slot-scope="scope">
                    <a :href="scope.row.img" target="_blank">
                        <el-image :src="scope.row.img"></el-image>
                    </a>
                </template>
            </el-table-column>
            <el-table-column
                    prop="introduce"
                    label="介绍">
            </el-table-column>
            <el-table-column
                    label="补充图片">
                <template slot-scope="scope">
                    <a v-for="supple in scope.row.supplementImages"
                       :href="supple.url"
                       target="_blank">
                        <el-image :src="supple.url"></el-image>
                    </a>
                </template>
            </el-table-column>
            <el-table-column
                    label="答案">
                <template slot-scope="scope">
                    <a :href="scope.row.answer" target="_blank">
                        <el-image :src="scope.row.img"></el-image>
                    </a>
                </template>
            </el-table-column>
            <el-table-column
                    label="提示">
                <template slot-scope="scope">
                    <a :href="scope.row.tip" target="_blank">
                        <el-image :src="scope.row.img"></el-image>
                    </a>
                </template>
            </el-table-column>
            <el-table-column
                    label="学科">
                <template slot-scope="scope">
                    <el-tag v-for="s in scope.row.subject">{{s}}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="思维">
                <template slot-scope="scope">
                    <el-tag v-for="t in scope.row.think">{{t.type}}-{{t.difficulty}}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="能力">
                <template slot-scope="scope">
                    <el-tag v-for="a in scope.row.ability">{{a.type}}-{{a.difficulty}}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="知识">
                <template slot-scope="scope">
                    <el-tag v-for="k in scope.row.knowledge">{{k.type}}-{{k.difficulty}}</el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="place" label="地点"></el-table-column>
            <el-table-column prop="scene" label="场景"></el-table-column>
            <el-table-column prop="character" label="人物"></el-table-column>
            <el-table-column prop="tool" label="道具"></el-table-column>
            <el-table-column prop="problem" label="问题承载"></el-table-column>
            <el-table-column prop="result" label="收获习得"></el-table-column>
            <el-table-column prop="cost" v-if="user.type === 0" label="耗时(s)"></el-table-column>
            <el-table-column
                    fixed="right"
                    label="操作"
                    width="50">
                <template slot-scope="scope">
                    <el-button type="primary" icon="el-icon-edit" size="small" circle
                               @click="edit(scope.$index, scope.row)"></el-button>
                    <div></div>
                    <el-button type="danger" icon="el-icon-delete" size="small" circle
                               @click="del(scope.$index, scope.row)"></el-button>
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
                    <el-select v-model="caseObj.book_id" placeholder="请选择">
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
                               :file-list="caseObj.supplementImages"
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
                                v-for="(item,index) in subject"
                                :key="index"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item v-for="(t,index) in caseObj.think" label="思维" prop="think">
                    <el-select v-model="t.type" placeholder="属性">
                        <el-option
                                v-for="(item,index) in think"
                                :key="index"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                    <el-select v-model="t.difficulty" placeholder="难度">
                        <el-option
                                v-for="(item,index) in difficulty"
                                :key="index"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                    <el-button v-if="caseObj.think.length>1" type="danger" icon="el-icon-delete" circle
                               @click="deleteThink(index)"></el-button>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-plus" @click="addThink"></el-button>
                </el-form-item>
                <el-form-item v-for="(t,index) in caseObj.ability" label="能力">
                    <el-select v-model="t.type" placeholder="属性">
                        <el-option
                                v-for="(item,index) in ability"
                                :key="index"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                    <el-select v-model="t.difficulty" placeholder="难度">
                        <el-option
                                v-for="(item,index) in difficulty"
                                :key="index"
                                :label="item"
                                :value="item">
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
                                v-for="(item,index) in knowledge"
                                :key="index"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                    <el-select v-model="t.difficulty" placeholder="难度">
                        <el-option
                                v-for="(item,index) in difficulty"
                                :key="index"
                                :label="item"
                                :value="item">
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
                                v-for="(item,index) in problem"
                                :key="index"
                                :label="item"
                                :value="item">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="收获习得" prop="result">
                    <el-select v-model="caseObj.result" placeholder="请选择">
                        <el-option
                                v-for="(item,index) in result"
                                :key="index"
                                :label="item"
                                :value="item">
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
            ...mapState(['user'])
        },
        data() {
            return {
                cases: [],
                caseObj: {
                    id: 0,
                    book_id: '',
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
                    start: 0,
                },
                books: [],
                dialogVisible: false,
                active: 0,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule0: {
                    book_id: [{required: true, message: '请选择书名', trigger: 'blur'}],
                    pageNumber: [{required: true, message: '请输入页码', trigger: 'blur'}],
                    title: [{required: true, message: '请输入案例题目', trigger: 'blur'}],
                    introduce: [{required: true, message: '请输入案例介绍', trigger: 'blur'}],
                    img: [{required: true, message: '请上传案例图片', trigger: 'blur'}],
                    answer: [{required: true, message: '请输入答案', trigger: 'blur'}],
                    tip: [{required: true, message: '请输入提示', trigger: 'blur'}],
                },
                rule1: {
                    subject: [{required: true, message: '请选择', trigger: 'blur'}],
                    think: [{type: 'array', required: true, message: '请选择', trigger: 'blur'}],
                    ability: [{required: true, message: '请选择', trigger: 'blur'}],
                    knowledge: [{required: true, message: '请选择', trigger: 'blur'}],
                },
                rule2: {
                    place: [{required: true, message: '请输入', trigger: 'blur'}],
                    scene: [{required: true, message: '请输入', trigger: 'blur'}],
                    character: [{required: true, message: '请输入', trigger: 'blur'}],
                    tool: [{required: true, message: '请输入', trigger: 'blur'}],
                    problem: [{required: true, message: '请选择', trigger: 'blur'}],
                    result: [{required: true, message: '请选择', trigger: 'blur'}],
                },
                subject: ["数学", '编程', 'AI'],
                think: ["逻辑思维", "抽象思维", "计算思维", "分布思维"],
                ability: ["观察力", "分析力", "实践力"],
                knowledge: ["数学推理", "空间想象"],
                difficulty: ["容易", "一般", "困难"],
                problem: ["拼图", "流程图", "运算", "代码逻辑", "观察", "编程环境"],
                result: ["单一问题训练", "综合问题解决", "工具使用技巧"],
                upload: {
                    type: "ocr",
                },
                mode: 'create',
            }
        },
        methods: {
            showDialog() {
                this.dialogVisible = true;
                this.caseObj.start = (new Date()).getTime();
                this.mode = 'create';
            },
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
                            if (this.caseObj.id > 0) {
                                axios.put('/documents/' + this.caseObj.id, this.caseObj)
                                    .then((response) => {
                                        this.dialogVisible = false;
                                        this.active = 0;
                                        this.caseObj = {};
                                        this.$refs.form0.resetFields();
                                        this.$refs.form1.resetFields();
                                        this.$refs.form2.resetFields();
                                        this.getCases();
                                    })
                                    .catch((error) => {
                                        console.log(error);
                                        this.$message.error('添加失败，请稍后重试');
                                    });
                            } else {
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
                                    });
                            }
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
            uploadSupplementImg(res, file, fileList) {
                this.caseObj.supplementImages.push({
                    name: file.name,
                    url: file.response.path,
                    uid: file.uid,
                    status: file.status
                });
            },
            removeSupplementImg(file, fileList) {
                this.caseObj.supplementImages = fileList;
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
                this.caseObj = row;
                this.caseObj.start = (new Date().getTime());
                this.dialogVisible = true;
                this.mode = 'update';
            },
            del(index, row) {
                axios.delete('/documents/' + row.id)
                    .then((response) => {
                        this.getCases();
                    })
                    .catch((error) => {
                        console.log(error);
                        this.$message.error(error.response.message);
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