import axios from "axios";

export async function loadAsistencias(anho, nues, espe) {
    try {
        let response = await axios.post(route("programa.asistencias"), {
            anho,
            nues,
            espe
        });

        return response.data;
    } catch (error) {
        console.log(error);
        return null;
    }
}
