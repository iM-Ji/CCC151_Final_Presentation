CREATE TABLE Rice (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Variety VARCHAR(255),
    GrowthStage VARCHAR(255)
);
CREATE TABLE Symptoms (
    ID INT PRIMARY KEY,
    Description VARCHAR(255),
    SeverityLevel VARCHAR(255),
    LocationOnPlant VARCHAR(255),
    FOREIGN KEY (ID) REFERENCES Rice(ID) ON DELETE CASCADE
);
CREATE TABLE DiseaseType (
    ID INT PRIMARY KEY,
    Name VARCHAR(255),
    Description VARCHAR(255),
    FOREIGN KEY (ID) REFERENCES Rice(ID) ON DELETE CASCADE
);
CREATE TABLE DetectionResult (
    DetectionID INT AUTO_INCREMENT PRIMARY KEY,
    RiceID INT,
    SymptomID INT,
    DiseaseTypeID INT,
    ConfidenceLevel VARCHAR(255),
    Timestamp TIMESTAMP,
    FOREIGN KEY (RiceID) REFERENCES Rice(ID),
    FOREIGN KEY (SymptomID) REFERENCES Symptoms(ID),
    FOREIGN KEY (DiseaseTypeID) REFERENCES DiseaseType(ID)
);