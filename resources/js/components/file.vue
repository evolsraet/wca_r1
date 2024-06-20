<template>
    <div class="file-upload-container">
      <div class="preview-container">
        <div v-for="(file, index) in files" :key="index" class="file-preview">
          <img :src="file.url" :alt="file.name" class="image-preview">
          <div @click="removeFile(index)" class="remove-button">
            <img src="../../img/Icon-black-close.png" alt="Remove" class="remove-icon">
          </div>
        </div>
        <div v-for="n in (5 - files.length)" :key="'empty-' + n" class="empty-preview">
          <img src="../../img/image.png" alt="empty" width="50px">
        </div>
      </div>
      <input type="file" ref="fileInputRef" style="display:none" @change="handleFileChange" multiple>
      <button type="button" class="btn btn-fileupload w-100" @click="triggerFileInput">
        파일 첨부
      </button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isDragging: false,
        files: [],  // files 배열 초기화
      };
    },
    methods: {
      triggerFileInput() {
        this.$refs.fileInputRef.click();
      },
      handleFileChange(event) {
        const selectedFiles = Array.from(event.target.files);
        this.addFiles(selectedFiles);
      },
      handleDrop(event) {
        this.isDragging = false;
        const droppedFiles = Array.from(event.dataTransfer.files);
        this.addFiles(droppedFiles);
      },
      addFiles(newFiles) {
        const remainingSlots = 5 - this.files.length;
        if (newFiles.length > remainingSlots) {
          alert('최대 5개의 파일만 가능합니다');
          newFiles = newFiles.slice(0, remainingSlots);
        }
        newFiles.forEach(file => {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.files.push({ file, url: e.target.result });
            // 파일이 추가되었을 때 file-attached 이벤트 내보내기
            this.$emit('file-attached');
          };
          reader.readAsDataURL(file);
        });
      },
      removeFile(index) {
        this.files.splice(index, 1);
      }
    }
  };
  </script>
  
  <style scoped>
  .file-upload-container {
    text-align: center;
  }
  .file-label {
    display: inline-block;
    padding: 20px;
    border: 2px dashed #cccccc;
    cursor: pointer;
    margin-bottom: 20px;
  }
  .preview-container {
    display: flex;
    overflow-x: scroll;
    gap: 10px;
    padding: 10px;
  }
  .file-preview, .empty-preview {
    flex: 0 0 auto;
    width: 100px;
    height: 100px;
    position: relative;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
  }
  .image-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .remove-button {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: white;
    box-shadow: 2px 2px 7px rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    padding: 5px;
  }
  .empty-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #aaa;
  }
  .remove-icon {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  </style>
  