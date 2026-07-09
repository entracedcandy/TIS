function startFace(){

    let getlabels = document.getElementById("files").value;
    let nm = document.getElementById("nm").value;
    let ps = document.getElementById("ps").value;
    let labels = getlabels.split("|");
    document.getElementById("fc").innerHTML = "";

    document.getElementById("video-frame").innerHTML = "<video id='video' width='600' height='450' autoplay>"

    const video = document.getElementById("video");
    
    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri("../../cpar/asset/face-api/models"),
        faceapi.nets.faceRecognitionNet.loadFromUri("../../cpar/asset/face-api/models"),
        faceapi.nets.faceLandmark68Net.loadFromUri("../../cpar/asset/face-api/models"),
        faceapi.nets.faceExpressionNet.loadFromUri("../../cpar/asset/face-api/models")
    ]).then(startWebcam);
    
    function startWebcam() {
        navigator.mediaDevices
        .getUserMedia({
            video: true,
            audio: false,
        })
        .then((stream) => {
            video.srcObject = stream;
        })
        .catch((error) => {
            console.error(error);
        });
    }
    
    function getLabeledFaceDescriptions() {
        return Promise.all(
            labels.map(async (label) => {
                const descriptions = [];
                for (let i = 1; i <= 1; i++) {
                const img = await faceapi.fetchImage(`./asset/face-api/labels_new/${label}`);
                const detections = await faceapi
                    .detectSingleFace(img)
                    .withFaceLandmarks()
                    .withFaceDescriptor();
                descriptions.push(detections.descriptor);
                }
                return new faceapi.LabeledFaceDescriptors(label, descriptions);
            })
        );
    }

    function loading(){
        Swal.fire({
            title: 'Loading',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });
    }

    function notif(status, msg){
        Swal.fire({
            position: 'center',
            icon: status,
            title: msg,
            showConfirmButton: false,
            timer: 2500
        });
    }
    
    video.addEventListener("play", async () => {
        const labeledFaceDescriptors = await getLabeledFaceDescriptions();
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);
    
        const canvas = faceapi.createCanvasFromMedia(video);
        document.body.append(canvas);
    
        const displaySize = { width: video.width, height: video.height };
        faceapi.matchDimensions(canvas, displaySize);

        let id = "";
        let count_detect = 0;
        let count_second = 0;
        let name = "";

        loading();
    
        let myIntv = setInterval(async () => {
    
            count_second++;

            const detections = await faceapi
                .detectAllFaces(video)
                .withFaceLandmarks()
                .withFaceDescriptors();
        
            const resizedDetections = faceapi.resizeResults(detections, displaySize);
        
            canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
        
            const results = resizedDetections.map((d) => {
                return faceMatcher.findBestMatch(d.descriptor);
            });

            if(results.length > 0 && results.length < 2){
                let data_id = results[0].label.split(",");

                if(id === ""){
                    id = data_id[0];
                    name = data_id[1];
                }else{
                    if(count_detect <= 10){
                        if(id !== data_id[0]){
                            id = data_id[0];
                            name = data_id[1];
                            count_detect = 1;
                        }
                    }
                }
                count_detect++;
            }

            console.log(count_second);

            if(count_detect > 10){

                // video.pause();

                // const mediaStream = video.srcObject;
                // const tracks = mediaStream.getTracks();
                // tracks[0].stop();
                // tracks.forEach(track => track.stop());

                clearInterval(myIntv);

                // console.log("a");

                var dataJson = { [nm]: ps, id: id };

                document.getElementById("hmis_login").classList.remove("disabled");
                document.getElementById("attd_login").classList.remove("disabled");
                document.getElementById("ai_login").classList.remove("disabled");
                
                Swal.close();
                $('#modalLogin').modal('hide');
                // $('#modalOTP').modal('show');

                // document.getElementById("otp_1").focus();
                // document.getElementById("otp_2").focus();
                
                // $.ajax({
                //     url : "../cp_login/Login/sendOtp",
                //     type: 'post',
                //     dataType: 'json',
                //     data: dataJson,            
                //     success : function(data)
                //     {   
                //         // console.log(data);

                //         console.log("b");

                //         Swal.close();
                //         $('#modalLogin').modal('hide');
                //         $('#modalOTP').modal('show');
                //     }  
                // });


                // $.ajax({
                //     url : "../cpar/Login_FC/GL",
                //     type: 'post',
                //     dataType: 'json',
                //     data: dataJson,            
                //     success : function(data)
                //     {   
                //         // console.log(data);

                //         clearInterval(myIntv);

                //         video.pause();

                //         const mediaStream = video.srcObject;
                //         const tracks = mediaStream.getTracks();
                //         tracks[0].stop();
                //         tracks.forEach(track => track.stop());

                //         Swal.close();
                //         $('#modalLogin').modal('hide');
                //         notif("success", "Selamat Datang, " + name);

                //         if(data[0].username === null){
                //             document.getElementById("attd_login").disabled = true;
                //             document.getElementById("attd_login").classList.add("disabled");
                //         }

                //         let inp = document.createElement("INPUT");
                //         inp.setAttribute("type", "text");
                //         inp.setAttribute("name", "usr");
                //         inp.setAttribute("value", data[0].username);
                //         inp.setAttribute("hidden", "TRUE");
                //         document.getElementById("attd").appendChild(inp);
                        
                //         let inpp = document.createElement("INPUT");
                //         inpp.setAttribute("type", "password");
                //         inpp.setAttribute("name", "pass");
                //         inpp.setAttribute("value", data[0].password);
                //         inpp.setAttribute("hidden", "TRUE");
                //         document.getElementById("attd").appendChild(inpp);

                //         if(data[0].hmis_login_user === ""){
                //             document.getElementById("hmis_login").disabled = true;
                //             document.getElementById("hmis_login").classList.add("disabled");
                //         }

                //         let ucer = document.createElement("INPUT");
                //         ucer.setAttribute("type", "text");
                //         ucer.setAttribute("name", "huc");
                //         ucer.setAttribute("value", data[0].hmis_login_user);
                //         ucer.setAttribute("hidden", "TRUE");
                //         document.getElementById("hmis").appendChild(ucer);

                //         let pace = document.createElement("INPUT");
                //         pace.setAttribute("type", "text");
                //         pace.setAttribute("name", "hps");
                //         pace.setAttribute("value", data[0].hmis_login_pass);
                //         pace.setAttribute("hidden", "TRUE");
                //         document.getElementById("hmis").appendChild(pace);

                //         if(data[0].integrasi_login_user === ""){
                //             document.getElementById("ai_login").disabled = true;
                //             document.getElementById("ai_login").classList.add("disabled");
                //         }

                //         let icer = document.createElement("INPUT");
                //         icer.setAttribute("type", "text");
                //         icer.setAttribute("name", "iuc");
                //         icer.setAttribute("value", data[0].integrasi_login_user);
                //         icer.setAttribute("hidden", "TRUE");
                //         document.getElementById("integrasi").appendChild(icer);

                //         let iace = document.createElement("INPUT");
                //         iace.setAttribute("type", "text");
                //         iace.setAttribute("name", "ips");
                //         iace.setAttribute("value", data[0].integrasi_login_pass);
                //         iace.setAttribute("hidden", "TRUE");
                //         document.getElementById("integrasi").appendChild(iace);

                //         setTimeout(function() {
                //             $('#modalOpsi').modal('show');
                //         }, 2500);
                //     }  
                // });
            }else if(count_second > 29){
                clearInterval(myIntv);
                Swal.close();
                $('#modalLogin').modal('hide');
                notif("error", "Gagal Login");

                setTimeout(function() {
                    location.reload();
                }, 2500);
            }
        }, 100);
    });
}

function loginInto(value){
    console.log(value);
}

function inputOtp(val, pos){
    document.getElementById(pos).value = val.replace(/[^0-9]/g,'');

    let value_now = document.getElementById(pos).value;

    if(value_now){
        if(pos === "otp_1"){
            document.getElementById("otp_2").focus();
        }else if(pos === "otp_2"){
            document.getElementById("otp_3").focus();
        }else if(pos === "otp_3"){
            document.getElementById("otp_4").focus();
        }else if(pos === "otp_4"){
            document.getElementById("otp_5").focus();
        }else if(pos === "otp_5"){
            document.getElementById("otp_6").focus();
        }else if(pos === "otp_6"){
            document.getElementById("cnf_otp").focus();
        }
    }
}

$('#modalOTP').on('shown.bs.modal', function () {
    $('#otp_1').focus();
})  