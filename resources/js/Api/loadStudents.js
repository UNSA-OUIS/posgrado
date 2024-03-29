import axios from "axios";

export async function loadPosgradoStudents(anho, nues, espe) {
    try {
        let response = await axios.post(route("students.posgrado"), {
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
