from typing import List, Optional
from pydantic import BaseModel
from datetime import datetime

class ReporteFiltro(BaseModel):
    campos: List[str]

class ReporteSalas(BaseModel):
    nom_sala: str
    capacidad: int
    nom_sede: str

class ReporteSedes(BaseModel):
    nom_sede: str
    direccion: str
    municipio: str

class ReporteJuzgados(BaseModel):
    nom_juzgado: str
    nom_sede: str

class ReporteUsuarios(BaseModel):
    nombres: str
    apellidos: str
    email: str
    cargo: str
    nom_sede: str
    nom_juzgado: str
    rol: str

class ReporteReservas(BaseModel):
    descripcion: str
    fecha: datetime
    hora_inicio: datetime
    hora_fin: datetime
    estado: str
    nom_sala: str
    usuario: str
