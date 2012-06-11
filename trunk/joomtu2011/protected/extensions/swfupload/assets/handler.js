/**
 * 取消上传队列
 * @param instance
 */
function cancelQueue(instance) {
	instance.stopUpload();
	var stats;
	do {
		stats = instance.getStats();
		instance.cancelUpload();
	}while (stats.files_queued !== 0);
}

/**
 * 打开选择文件上传对话框
 */
function fileDialogStart() {

}

/**
 * 上传队列
 * @param file
 */
function fileQueued(file) {
	try {
		//showMessage("文件被添加到队列。");
	} catch (ex) {
		this.debug(ex);
	}
}

/**
 * 选择文件对话框关闭
 * @param numFilesSelected
 * @param numFilesQueued
 */
function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (this.getStats().files_queued > 0) {
			
		}
		this.startUpload();
	} catch (ex) {
		this.debug(ex);
	}
}

/**
 * 开始上传
 * @param file
 * @returns {Boolean}
 */
function uploadStart(file) {
	try {

	} catch (ex) {
		
	}
	return true;
}

/**
 * 上传进度
 * @param file
 * @param bytesLoaded
 * @param bytesTotal
 */
function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {

	} catch (ex) {
		this.debug(ex);
	}
}

/**
 * 文件上传成功
 * @param file
 * @param serverData
 */
function uploadSuccess(file, serverData) {
	serverData = (new Function( "return " + serverData))();
	try {
		if(serverData.status > 0){
			showMessage(serverData.message,1);
		}else{
			showMessage(serverData.message);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

/**
 * 所有文件上传完成
 * @param file
 */
function uploadComplete(file) {
	try {
		if (this.getStats().files_queued === 0) {
			
		} else {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}

}

/**
 * 上传错误
 * @param file
 * @param errorCode
 * @param message
 */
function uploadError(file, errorCode, message) {
	try {
		switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:showMessage("HTTP请求错误。");break;
			case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:showMessage("配置错误。");break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:showMessage("上传失败。");break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR:showMessage("服务器IO错误。");break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:showMessage("安全问题导致错误。");break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:showMessage("上传文件个数限制错误。");break;
			case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:showMessage("没有发现上传的文件。");break;
			case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:showMessage("验证失败，取消上传。");break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:if (this.getStats().files_queued === 0) showMessage("取消上传。");break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:showMessage("停止上传。");break;
			default:showMessage("未知错误，错误代码：" + errorCode + "，信息：" + message + "。");break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

/**
 * 上传队列
 * @param file
 * @param errorCode
 * @param message
 */
function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			showMessage("上传的文件个数已经达到上限。");return;
		}
		switch(errorCode) {
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:showMessage("文件太大。");break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:showMessage("不能上传0字节的文件。");break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:showMessage("无效的问题件类型。");break;
			case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:showMessage("您选择的文件太多 " );break;
			default:showMessage("错误代码：" + errorCode + "，信息： " + message);break;
		}
	} catch (ex) {
		this.debug(ex);
    }
}