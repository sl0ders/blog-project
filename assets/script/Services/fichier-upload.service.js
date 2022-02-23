import axios from "axios";

class FileUploadService {
    upload(file, onUploadProgress) {
        let formData = new FormData();
        formData.append("file", file);
        return axios.post("/upload", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
            onUploadProgress,
        });
    }
    getFiles() {
        return axios.get("/files");
    }
}
export default new FileUploadService();