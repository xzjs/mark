<template>
    <div>
        <el-button v-if="user.type === 0" type="primary" icon="el-icon-edit" @click="dialogVisible=true">添加</el-button>
        <el-table
                border
                :data="books"
                style="width: 100%">
            <el-table-column
                    prop="id"
                    label="编号"
                    width="50px">
            </el-table-column>
            <el-table-column
                    prop="topic"
                    label="题目"
                    width="180px">
            </el-table-column>
            <el-table-column
                    prop="legends"
                    label="图例">
                <template slot-scope="scope">
                    <div v-for="legend in scope.row.legends">
                        <el-image :src="legend" fit="contain"></el-image>
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                    prop="answers"
                    label="答案">
                <template slot-scope="scope">
                    <div v-for="answer in scope.row.answers">
                        <el-image :src="answer" fit="contain"></el-image>
                    </div>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog
                title="添加案例"
                :visible.sync="dialogVisible"
                width="80%"
                :before-close="handleClose">
            <el-form :model="book" :rules="rule" ref="form">
                <el-form-item label="题目" label-width="100px" prop="topic">
                    <el-input
                            type="textarea"
                            :rows="2"
                            placeholder="请输入题目内容"
                            v-model="book.topic">
                    </el-input>
                </el-form-item>
                <el-form-item label="图例" label-width="100px" prop="legend">
                    <el-upload
                            ref="legend"
                            list-type="picture-card"
                            action="/upload"
                            name="img"
                            accept="image/*"
                            :on-success="uploadLegendSuccess"
                            :headers="{'X-XSRF-TOKEN':csrfToken}">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="答案" label-width="100px" prop="answer">
                    <el-upload
                            ref="answer"
                            list-type="picture-card"
                            action="/upload"
                            name="img"
                            accept="image/*"
                            :on-success="uploadAnswerSuccess"
                            :headers="{'X-XSRF-TOKEN':csrfToken}">
                        <i class="el-icon-plus"></i>
                    </el-upload>
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
        name: "Book",
        computed: {
            ...mapState(['user'])
        },
        data() {
            return {
                book: {
                    topic: '',
                    legends: [],
                    answers: [],
                },
                books: [],
                dialogVisible: false,
                csrfToken: this.$cookies.get('XSRF-TOKEN'),
                rule: {
                    topic: [{required: true, message: '请输入书名', trigger: 'blur'}],
                    legends: [{required: true, message: '请上传pdf', trigger: 'blur'}],
                    answers: [{required: true, message: '请上传pdf', trigger: 'blur'}],
                }
            }
        },
        methods: {
            uploadLegendSuccess(response, file, fileList) {
                this.book.legends.push(response.path);
            },
            uploadAnswerSuccess(response, file, fileList) {
                this.book.answers.push(response.path);
            },
            handleClose(done) {
                this.$confirm('确认关闭？')
                    .then(_ => {
                        this.book = {};
                        this.$refs.legend.clearFiles();
                        this.$refs.answer.clearFiles();
                        done();
                    })
                    .catch(_ => {
                    });
            },
            create() {
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        axios.post('/books', this.book)
                            .then((response) => {
                                this.getBooks();
                                this.$refs.form.resetFields();
                                this.$refs.legend.clearFiles();
                                this.$refs.answer.clearFiles();
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
            },
            getBooks() {
                axios.get('books')
                    .then((response) => {
                        this.books = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            }
        },
        mounted() {
            this.getBooks();
        }
    }
</script>

<style scoped>

</style>
