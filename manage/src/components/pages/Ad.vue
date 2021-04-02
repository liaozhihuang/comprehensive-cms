<template>
    <div clas="app">
        <div class="crumbs">
            <AppstoreOutlined /> banner列表
        </div>
        <div class="container">
            <div class="handle-box">
                <a-button type="primary" @click="addView()" class="mr10"><template #icon><PlusOutlined /></template>创建</a-button>
                <a-input v-model:value="search" placeholder="banner名称" class="handle-input mr10"></a-input>
                  <a-button type="primary" @click="handleSearch">
                    <template #icon><SearchOutlined /></template>搜索
                </a-button>
            </div>
            <!-- banner列表 -->
			<a-table :columns="columns" :data-source="adAll" :indentSize="30" :pagination="pagination" :rowKey='record=>record.id'>
                <template #img_url="{ record }">
                    <div><img style="width:100px;heigth:100px" :src="record.img_url" /></div>
                </template>

                <template #status="{ status,record }">
                    <a-popconfirm placement="topRight" ok-text="是" cancel-text="否"  @confirm="statusOperation(record)">
                        <template  #title>
                            <p v-if="record.status == 1">隐藏此图片？</p>
                            <p v-else>显示此图片？</p>
                        </template>
                        <a-tag  :color="record.status == 1 ? 'green' :'red' " > {{record.status == 1 ?'正常' :"禁用" }}</a-tag>
                    </a-popconfirm>
                </template>

				<!-- 按钮 -->
				<template #operation="{record,index }" >
					<div class="editable-row-operations">
						<a-button size="small" type="link" @click="editView(record)" ><template #icon><EditOutlined /></template>编辑</a-button>
                        <a-popconfirm placement="topRight" ok-text="是" cancel-text="否" @confirm="delAd(record,index)">
							<template #title>
								<p>确定删除?</p>
							</template>
							<a-button size="small" type="link"> <template #icon><DeleteOutlined /></template>删除</a-button>
						</a-popconfirm>

					</div>
				</template>

			</a-table>
        </div>

        <!--模态窗 -->
        <template>
            <div>
                <a-modal 
                    :title="ModalText"
                    :visible="visible"
                    :confirm-loading="confirmLoading"
                    :centered="true"
                    :maskClosable="false"
                    @ok="handleSubmits"
                    width="50%"
                    :zIndex="99"
                    @cancel="handleCancel">
                    <a-form :model="form" :label-col="labelCol" :wrapper-col="wrapperCol">
                        <a-form-item label="广告名称" >
                            <a-input v-model:value="form.ad_name" placeholder="广告名称" />
                        </a-form-item>

                        <a-form-item label="广告组" >
                            <a-select v-model:value="form.group_sign" ref="select">
                                <a-select-option v-for="group in groupAll" :value="group.group_sign"> {{group.group_name}}</a-select-option>
                            </a-select>
                        </a-form-item>


                        <a-form-item label="跳转链接" >
                            <a-input v-model:value="form.link" placeholder="跳转链接" />
                        </a-form-item>

                        <a-form-item label="图片">
                            <a-button @click="images()"> <CloudUploadOutlined />上传 </a-button>
                            <div class="ant-upload-list ant-upload-list-picture">
                                <div v-for="(image,key) in imgList">
                                    <span>
                                        <div class="ant-upload-list-item ant-upload-list-item-done ant-upload-list-item-list-type-picture">
                                            <div class="ant-upload-list-item-info">
                                                <span>
                                                    <a class="ant-upload-list-item-thumbnail" :href="image.img_url" target="_blank" rel="noopener noreferrer">
                                                        <img :src="image.img_url" class="ant-upload-list-item-image">
                                                    </a>
                                                    <a target="_blank" rel="noopener noreferrer" class="ant-upload-list-item-name ant-upload-list-item-name-icon-count-1" :title="image.name" :href="image.img_url">{{image.name}}</a>
                                                    
                                                    <span class="ant-upload-list-item-card-actions picture">
                                                        <a title="删除文件" href="javascript:;" @click="delCover()">
                                                            <span title="删除文件" tabindex="-1" role="img" aria-label="delete" class="anticon anticon-delete">  <DeleteOutlined /></span>
                                                        </a>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                                </div>       
                            </div>
                        </a-form-item>

                        <a-form-item label="排序" >
                            <a-input v-model:value="form.sort" placeholder="排序" />
                        </a-form-item>

                        <a-form-item label="状态">
                            <a-radio-group v-model:value="form.status">
                                <a-radio :value="1">显示</a-radio>
                                <a-radio :value="0">隐藏</a-radio>
                            </a-radio-group>
                        </a-form-item>

                    </a-form>
                </a-modal>
            </div>
        </template>





    </div>
</template>


<script>
const columns = [
    {
		title: 'ID',
		dataIndex: 'id',
		slots: { customRender: 'id' },
		align: 'center'
    }, 
	{
		title: '名称',
		dataIndex: 'ad_name',
		slots: { customRender: 'ad_name' },
		align: 'center'
    },  
    {
        title: '图片',
        dataIndex: 'img_url',
        width: '15%',
        slots: { customRender: 'img_url' },
        align: 'center'
    },
    {
        title: '地址',
        dataIndex: 'link',
        slots: { customRender: 'link' },
        align: 'center'
    },
    {
        title: '状态',
        dataIndex: 'status',

        slots: { customRender: 'status' },
        align: 'center'
    },
	{
		title: '创建时间',
		dataIndex: 'add_time',
		slots: { customRender: 'add_time' },
		align: 'center'
    },
	{
		title: '操作',
		key: 'operation',
		slots: { customRender: 'operation' },
		align: 'center'
	},
];

import {fetchPost,fetchGet} from '../../utils/http'
import moment from 'moment';
import 'moment/dist/locale/zh-cn';

import {
    CloudUploadOutlined,
    PlusOutlined,
    DeleteOutlined,
    SearchOutlined,
    EditOutlined,
    AppstoreOutlined
} from '@ant-design/icons-vue';

const forms = {
    status:1
} 

export default {
    components: {
        CloudUploadOutlined,
        PlusOutlined,
        DeleteOutlined,
        EditOutlined,
        SearchOutlined,
        AppstoreOutlined,
    },
	data() {
		return {
            form:{},
            adAll:[], //列表
			columns,
			search:"", //搜索
			labelCol: { span: 4 },
			wrapperCol: { span: 18 },
			ModalText:'', //模态窗标题
			visible: false, //模态窗状态
			confirmLoading: false,
            pagination: {
                pageNo: 1, //分页
                pageSize: 10, // 默认每页显示数量
                showSizeChanger: false, // 显示可改变每页数量
                pageSizeOptions: ["10", "20", "50", "100"], // 每页数量选项
                showTotal: total => `共${total}条`, // 显示总数
                onShowSizeChange: (pageSize) =>(this.pageSize = pageSize), // 改变每页数量时更新显示
                onChange:this.onPageChange.bind(this), //点击页码事件
                total: 0, //总条数
                current: 0, //页码
                buildOptionText:pageSizeOptions => `${pageSizeOptions.value}条/页`,
            },

            image_visible:false, //图片窗口
            img_select:"", //选择图片的按钮
            imagesCount:1,  //可选择图片数量
            selectArr:[],
            imgList:[], //图片

		};
    },

    mounted() {
        this.form = JSON.parse(JSON.stringify(forms));
        this.getData();
        this.getGroup();
    },
   

	methods: {
		getData() {
            let url = '/ad';
            let that = this
            let pages = this.pagination;
			let params = {};
				params.pageIndex = pages.pageNo;
				params.pageSize = pages.pageSize;
				params.search = this.search;
            fetchGet(url,params).then(res => {
                var ret = res.data;
				this.adAll = ret.data;
				this.pagination.total = ret.count;
            })
        },

        getGroup(){
            let url = '/adGroup/getAdGroup';
            let that = this
            fetchGet(url).then(res => {
                var ret = res.data;
				this.groupAll = ret.data;
            })
        },

        //搜索
		handleSearch(e){
            this.pagination.pageNo = 1;
            this.pagination.current = 1;
			this.getData();
		},

		//分页
		onPageChange(page){
			this.pagination.current = page;
			this.pagination.pageNo = page;
			this.getData();
        },

        //添加窗口
        addView(){
            this.ModalText="添加广告";
            this.visible = true;
        },

        //编辑窗口
        editView(row){
            this.ModalText = "编辑";
            this.visible = true;
            this.form = row;

            //封面图片
            let imgUrl = {};
            let pos = row.img_url.lastIndexOf('\/'); // 查找最后一个/的位置
                imgUrl.name =  row.img_url.substring(pos + 1);
                imgUrl.img_url = row.img_url;
            this.imgList.push(imgUrl);
        },


        //提交数据
        handleSubmits(){
            let url = '';
            if(this.form.id != undefined){
				url = '/ad/updateAd';
			}else{
				url = '/ad/createAd';
			}
            fetchPost(url,this.form).then(res => {
                var ret = res.data;
                if(ret.code == 1){
					this.handleCancel();
                    this.$message.success(ret.msg);
                    this.getData();
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },


        //关闭窗口
        handleCancel(){
            this.visible = false;
            this.form = {};
            this.form  = JSON.parse(JSON.stringify(forms));
            this.imgList = [];
        },

        //删除
        delAd(row,index){
            let url = '/ad/delAd';
 			let params = {};
            params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    this.adAll.splice(index, 1);
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

        //用户操作
        statusOperation(row){
            let url = '/ad/statusOperation';
 			let params = {};
                params.id = row.id;
            fetchGet(url,params).then(res => {
               	var ret = res.data;
                if(ret.code == 1){
                    row.status = row.status == 1? 0 : 1;
                    this.$message.success(ret.msg);
                }else{
                    this.$message.error(ret.msg);
                    return false;
                }
            })
        },

	},
};
</script>
