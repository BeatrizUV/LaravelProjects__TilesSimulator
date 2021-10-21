<?php

namespace Simulador\Helpers;

use File;

/**
 * Clase encargada de gestionar los procesos relacionados con la gestión de imágenes del lado del servidor.
 * 
 * @author Beatriz Urbano Vega
 */
class ManageImages {
    /**
     * Método encargado de subir imágenes al servidor.
     * 
     * @param \Illuminate\Http\Resquest $request
     * @param string $imageName
     * @param string $input
     * @param string $path
     * @return boolean
     */
    public static function upload($request, $imageName, $input, $path) {
        $ok = false;
        
        // Comprobamos que el formulario trae adjunta una imagen
        if ($request->hasFile($input)) {
            // Recogemos la imagen temporal
            $tempFile = $request->file($input);
            
            // Si el archivo es válido
            if ($tempFile->isValid()) {
                // Montamos el path de destino de la imagen
                $destinationPath = env('UPLOAD_DIR') . '/' . $path;
                // Obtenemos la extensión de la imagen
                $extension = $tempFile->getClientOriginalExtension();
                // Creamos el nombre para la imagen
                $fileName = $imageName.'.'.$extension;
             
                // Revisamos que la imagen no exista ya en el servidor
                if (File::exists($destinationPath . '/' . $fileName)) {
                    // Y si existe la borramos
                    self::delete($path, $fileName) ;
                }
                
                // Y subimos la imagen al servidor
                $tempFile->move($destinationPath, $fileName);
                $ok = true;                
            }
        }
        
        return $ok;
    } 
    
    /**
     * Método encargado de subir al servidor imágenes generadas dinámicamente desde el simulador.
     * 
     * @param string $data
     * @param string $imageName
     * @param string $path
     * @return boolean
     */
    public static function saveDynamicImage($data, $imageName, $path) {
        try {
            // Codificamos en base64 el string de los datos de la imagen
            $tempImage = base64_decode($data);
            // Creamos una imagen temporal con los datos codificados
            $image = imagecreatefromstring($tempImage);
            // Establecemos el path de destino de la imagen (nombre y formato incluidos)
            $destinationPath = $path . '/' . $imageName;
            // Guardamos la imagen temporal en el path de destino establecido
            imagepng($image, $destinationPath);
            // Borramos la imagen temporal del servidor
            imagedestroy($image);
        } catch (Exception $ex) {
            // Si salta alguna excepción asignamos false al path de destino de la imagen
            $destinationPath = false;
        }
        
        // Devolvemos el path de destino de la imagen PNG creada
        return $destinationPath;
    } 
    
    /**
     * Método encargado de renombrar archivos del servidor.
     * 
     * @param type $path
     * @param type $oldImageName
     * @param type $newName
     * @return boolean
     */
    public static function renameFiles($path, $oldImageName, $newName) {
        $ok = false;
        // Obtenemos el formato de la imagen
        $tokens = explode('.', $oldImageName);
        // Obtenemos el path de la carpeta /public de la aplicación
        $publicPath = public_path();
        // Obtenemos el path completo de la imagen antigua
        $oldImage = $publicPath . '/' . env('UPLOAD_DIR') . '/' . $path . '/' . $oldImageName;
        // Obtenemos el path de la nueva imagen
        $newImage = $publicPath . '/' . env('UPLOAD_DIR') . '/' . $path . '/' . $newName . '.' . $tokens[1];
        
        // Si el nuevo nombre diferente del nombre antiguo
        if ($newName != $tokens[0]) {
            // Movemos la imagen nueva al nuevo directorio (realmente lo que se hace es una copia)
            if (File::move($oldImage, $newImage)) {
                // Y eliminamos la imagen antigua del servidor
                self::delete($path, $oldImageName);
                $ok = true;
            }
        }
        else {
            // Si el nuevo nombre y el antiguo son iguales no hacemos nada
            $ok = true;
        }
        
        // Devolvemos el resultado de la operación
        return $ok;
    }
    
    /**
     * Método encargado de eliminar una imagen del servidor (desde el panel de administración)
     * @param string $path
     * @param string $imageName
     * @return boolean
     */
    public static function delete($path, $imageName) {
        $flag = false;
        // Si la imagen existe
        if (File::exists(env('UPLOAD_DIR') . '/' . $path . '/' . $imageName)) {
            // La borramos
            File::delete(env('UPLOAD_DIR') . '/' . $path . '/' . $imageName);
            $flag = true;
        }
        
        // Y devolvemos el resultado de la operación
        return $flag;
    }
    
    /**
     * Método encargado de eliminar una imagen del servidor (desde el simulador)
     * @param string $image
     */
    public static function deleteTempImage($image) {
        // Si existe la imagen
        if (File::exists($image)) {
            // La eliminamos
            File::delete($image);
        }
    }
}